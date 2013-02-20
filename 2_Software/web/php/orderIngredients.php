<?php

# Autor: Anne, Molt
# Datum: 29.11.2012
# PHP  Datei die für die Bestellung von Zutaten zuständig ist
# Es werden Zutaten aus der Datenbank abgerufen und dannach als PDF Datei an die
# ensprechende E-mail Adresse bzw. von Supermark zugeschickt.



include 'dbOperations.php';

require 'fpdf.php';

session_start();
$userId = $_SESSION['user_id'];

//get user data
$query = "SELECT * from user WHERE user_id = $userId";
$result = dbRequest($query);
$customerRow = mysql_fetch_object($result);
$vorname = $customerRow->firstname;
$nachname = $customerRow->lastname;
$email = $customerRow->email;
$strasse = $customerRow->street;
$houseNumber = $customerRow->house_number;
$plz = $customerRow->zipcode;
$stadt = $customerRow->city;
$ingredientRows = count($_POST) / 3;
$targetEmail = 'anne.moldt73@gmail.com';  

//class nessessary to overwrite the Header()-function and the Footer()-function
class PDF extends FPDF {

    // Page header
    function Header() {
        // Logo
        $file = "../img/order-attachment-background.jpg";
        $this->Image($file, 0, 0, 210, 297);
    }

    //Page footer
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'B', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function addAnotherPage($vorname, $nachname, $strasse, $plz, $houseNumber, $stadt, $email) {
        $this->AddPage();
        $this->Ln(100);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 8, 'Buyer:', 0, 2);
        $this->Cell(0, 8, utf8_decode($vorname) . ' ' . utf8_decode($nachname), 0, 2, 'C');
        $this->Cell(0, 8, utf8_decode($strasse) . ' ' . utf8_decode($houseNumber), 0, 2, 'C');
        $this->Cell(0, 8, $plz . ' ' . utf8_decode($stadt), 0, 2, 'C');
        $this->Cell(0, 8, utf8_decode($email), 0, 2, 'C');
        $this->Cell(0, 8, 'Order:', 0, 2);
    }

}

//create pdf
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->addAnotherPage($vorname, $nachname, $strasse, $plz, $houseNumber, $stadt, $email);
 
$i = 1;
while ($i <= $ingredientRows) {
    if ($i % 14 == 0) {
        $pdf->addAnotherPage($vorname, $nachname, $strasse, $plz, $houseNumber, $stadt, $email);
    }
    if ($_POST['unitSelect' . $i] != 0) {
        $query = "SELECT unit_name FROM unit where unit_id= " . $_POST['unitSelect' . $i];
        $result = dbRequest($query);
        $row = mysql_fetch_object($result);
        $pdf->Cell(0, 8, $_POST['ingredientQuantity' . $i] . ' ' . utf8_decode($row->unit_name) . ' ' . utf8_decode($_POST['ingredientName' . $i]), 0, 2, 'C');
    } else {
        $pdf->Cell(0, 8, $_POST['ingredientQuantity' . $i] . ' ' . utf8_decode($_POST['ingredientName' . $i]), 0, 2, 'C');
    }

    $i++;
}

$pdf_path = '../img/upload/order.pdf';
$pdf_name = 'order.pdf';
//show PDF in browser
//$pdf->Output();
//store PDF in filesystem
$pdf->Output($pdf_name, 'F');

//create e-mail
$subject = "Order from cooking place";
$file_content = chunk_split(base64_encode(file_get_contents($pdf_name)));
$boundary = strtoupper(md5(uniqid(time())));
$header .= "MIME-Version: 1.0\r\n";
$header .= 'From: cookingplace' . "\r\n";
$header .= "Content-Type: multipart/mixed;\r\n boundary=$boundary\r\n";
//content of e-mail
$message = "This is a multi-part message in MIME format.\r\n\r\n";
$message .= "--" . $boundary . "\r\n";
$message .= "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
$message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$message .= "You have received an order from Cooking Place. See attachment for further information.\r\n";
$message .= "--" . $boundary . "\r\n";
//Attachment
$message .= "Content-Disposition: attachment;\r\n";
$message .= "Content-Type: Application/Octet-Stream; name=\"" . $pdf_name . "\"\r\n";
$message .= "Content-Transfer-Encoding: base64\r\n\r\n";
$message .= $file_content . "\r\n";
$message .= "--$boundary--";
//send e-mail
mail($targetEmail, $subject, $message, $header);
unlink($pdf_path);
//redirect
$url = "http://sfsuswe.com/~f12g22/?order=success";
header("HTTP/1.1 301 Moved Permanently");
header("Location: $url");
exit();
?>
