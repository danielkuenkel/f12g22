<?php

# Autor: Daniel Kuenkel
# Datum: 17.11.2012
#              
# Hier wird als php Script die Funktion Einloggen zusammengesetzt beim Prüfung
# die eingegebene  Daten mit der in der Datenbank sind



session_start();
include 'dbOperations.php';
$_SESSION['logged_in'] = 0;
$logon = $_GET['logon'];
$logonPassword = md5($_GET['password']);
$query = "SELECT * FROM user WHERE logon_name='$logon' and password='$logonPassword'";
$result = dbRequest($query);
// Mysql_num_row is counting table row
$count = mysql_num_rows($result);
header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<session>\n";
if ($count == 1) {
    $row = mysql_fetch_object($result);
    echo "<activate>$row->activate</activate>\n";
    echo "<registered>1</registered>\n";

    if ($row->activate == 1) {
        $_SESSION['logon_name'] = $row->logon_name;
        $_SESSION['user_id'] = $row->user_id;
        $_SESSION['logged_in'] = 1;
        $_SESSION['forename'] = $row->firstname;
        $_SESSION['surname'] = $row->lastname;
        $_SESSION['street'] = $row->street;
        $_SESSION['house_number'] = $row->house_number;
        $_SESSION['zip'] = $row->zipcode;
        $_SESSION['city'] = $row->city;
        $_SESSION['phone_number'] = $row->phone_number;
        echo "<userId>$row->user_id</userId>\n";
        echo "<logonName>$row->logon_name</logonName>\n";
        echo "<street>$row->street</street>\n";
        echo "<houseNumber>$row->house_number</houseNumber>\n";
        echo "<zip>$row->zipcode</zip>\n";
        echo "<city>$row->city</city>\n";
    }
} else {
    echo "<activate>0</activate>\n";
    echo "<registered>0</registered>\n";
}
echo "</session>";
?>