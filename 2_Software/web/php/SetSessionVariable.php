<?php

session_start();
if (isset($_POST['forename'])) {
    $_SESSION['forename'] = utf8_decode($_POST['forename']);
}
if (isset($_POST['surname'])) {
    $_SESSION['surname'] = utf8_decode($_POST['surname']);
}
if (isset($_POST['street'])) {
    $_SESSION['street'] = utf8_decode($_POST['street']);
}
if (isset($_POST['house_number'])) {
    $_SESSION['house_number'] = $_POST['house_number'];
}
if (isset($_POST['zip'])) {
    $_SESSION['zip'] = $_POST['zip'];
}
if (isset($_POST['city'])) {
    $_SESSION['city'] = utf8_decode($_POST['city']);
}
if (isset($_POST['phone_number'])) {
    $_SESSION['phone_number'] = $_POST['phone_number'];
}
?>
