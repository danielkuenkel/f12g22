<?php


# Autor: Daniel Kuenkel
# Datum: 17.11.2012
#              
# PHP  Datei die die  Funktion Ausloggen implementiert.
# Hier wird als php Script die Registrierung von Benutzer Daten zusammengesetzt.
# mit verschiedenen Angaben, die in der Datenbank hinzugefügt werden.




include 'dbOperations.php';
$logonName = $_GET['logon'];
$forename = $_GET['forename'];
$surname = $_GET['surname'];
$password = md5($_GET['password']);
$mail = $_GET['mail'];
$street = $_GET['street'];
$housenumber = $_GET['housenumber'];
$zip = $_GET['zip'];
$city = $_GET['city'];
$phone = $_GET['phone'] == "" ? NULL : $_GET['phone'];

$query = "SELECT * FROM user WHERE logon_name LIKE '$logonName'";
$result = dbRequest($query);
$logonExists = mysql_num_rows($result) > 0;
if (!$logonExists) {
    $query = "INSERT INTO user (logon_name, firstname, lastname, email, street, house_number, zipcode, city, phone_number, password) VALUES('$logonName', '$forename', '$surname', '$mail', '$street', '$housenumber', '$zip', '$city', '$phone', '$password')";
    dbUpdate($query) or die("database connection error. please try again later");
    $queryId = "SELECT * FROM user WHERE logon_name='$logonName'";
    $result = dbRequest($queryId);
    $row = mysql_fetch_object($result);

    $headers = 'From: cookinplace' . "\r\n" .
            'Content-type: text/html; charset=UTF-8' . "\r\n";
    $hash = md5($row->user_id . $logonName . $password);
    $subject = "Welcome to cooking place";
    $message = "<p>Hey $forename $surname!</p><p>Thank you that you've signed up!</p><p>Click <a href='http://www.sfsuswe.com/~f12g22/?activate=$hash&logon=$logonName'>here</a> to confirm your e-mail address and activate your account on cooking place.</p>";
    mail($mail, $subject, $message, $headers);
}
header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<user>\n";
if ($logonExists) {
    echo "<hasUser>1</hasUser>\n";
} else {
    echo "<hasUser>0</hasUser>\n";
    echo "<userId>$row->user_id</userId>\n";
}
echo "</user>\n";
?>