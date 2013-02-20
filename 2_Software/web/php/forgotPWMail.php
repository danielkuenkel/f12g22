<?php

# Autor: Daniel, Malkmus
# Datum: 22.11.2012
# PHP  Datei zum Implementieren der Funktion "Password vergessen".

include 'dbOperations.php';

$forgotMail = $_GET['forgotMail'];
$randomPassword = createRandomPassword();
$newPW = md5($randomPassword);
$query = "SELECT * FROM user WHERE email='$forgotMail'" or die("database connection error. please try again later");
$result = dbRequest($query);
$emailExists = mysql_num_rows($result) > 0;
if ($emailExists) {
    $query = "UPDATE user SET password='$newPW' WHERE email='$forgotMail'";
    dbUpdate($query) or die("database connection error. please try again later");
    $row = mysql_fetch_object($result);

    $headers = 'From: cookinplace' . "\r\n" .
            'Content-type: text/html; charset=UTF-8' . "\r\n";
    $subject = "Cooking place password recover";
    $message = "<p>Hey $row->firstname $row->lastname!</p><p>Your password was resetted.</p><p>Your recover password is $randomPassword</p>";

    $mailSend = mail($forgotMail, $subject, $message, $headers);
}

if (!$emailExists) {
    
}

function createRandomPassword()
{
//diese Funktion stellt ein neues Password her wenn das Alte vergisst wurde
    
    $chars = "abcdefghijkmnopqrstuvwxyz023456789";
    srand((double) microtime() * 1000000);
    $i = 0;
    $pass = '';

    while ($i <= 7) {
        $num = rand() % 33;
        $tmp = substr($chars, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }

    return $pass;
}

header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<forgotMail>\n";
if (!$emailExists) {
    echo "<hasEmail>0</hasEmail>\n";
    echo "<mailSend>0</mailSend>\n";
}elseif ($emailExists && !$mailSend) {
    echo "<hasEmail>1</hasEmail>\n";
    echo "<mailSend>0</mailSend>\n";
}elseif ($emailExists && $mailSend) {
    echo "<hasEmail>1</hasEmail>\n";
    echo "<mailSend>1</mailSend>\n";
    echo "<userId>$row->user_id</userId>\n";
}
echo "</forgotMail>\n";
?>
