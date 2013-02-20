<?php

# Autor: Daniel Kuenkel
# Datum: 17.11.2012
#              
# Diese Datei ruft das Anmelformular ab, damit ein Benutzer seine Angaben ändern 
# kann.

session_start();
include 'dbOperations.php';
$userId = $_SESSION["user_id"];
$forename = $_GET['forename'];
$surname = $_GET['surname'];
$password = $_GET['password'];
$street = $_GET['street'];
$housenumber = $_GET['housenumber'];
$zip = $_GET['zip'];
$city = $_GET['city'];
$phone = $_GET['phone'] == "" ? NULL : $_GET['phone'];
$query = "";
if ($password == "") {
    $query = "UPDATE user SET firstname='$forename', lastname='$surname', street='$street', house_number='$housenumber', zipcode='$zip', city='$city', phone_number='$phone' WHERE user_id = $userId";
} else {
    $password = md5($password);
    $query = "UPDATE user SET firstname='$forename', lastname='$surname', street='$street', house_number='$housenumber', zipcode='$zip', city='$city', phone_number='$phone', password='$password' WHERE user_id = $userId";
}
$result = dbUpdate($query);
header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<user>\n";
if (is_null($result)) {
    echo "<success>false</success>\n";
} else {
    $_SESSION['forename'] = $forename;
    $_SESSION['surname'] = $surname;
    $_SESSION['street'] = $street;
    $_SESSION['house_number'] = $housenumber;
    $_SESSION['zip'] = $zip;
    $_SESSION['city'] = $city;
    $_SESSION['phone_number'] = $phone;
    echo "<success>true</success>\n";
}
echo "</user>\n";
?>