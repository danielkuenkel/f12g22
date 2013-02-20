<?php


# Autor: Daniel Kuenkel
# Datum: 19.11.2012
#              
#  Hier wird als php Script die Funktion "von Favoriten löschen" implementiert



session_start();
include 'dbOperations.php';
$recipeId = $_GET['recipeId'];
$userId = $_SESSION['user_id'];
$query = "DELETE FROM favorites WHERE user_id = $userId AND recipe_id = $recipeId";
header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<favorite>\n";
$result = dbUpdate($query);
if (!$result) {
    echo "<status>error</status>\n";
} else {
    echo "<status>okay</status>\n";
    echo "<recipeId>$recipeId</recipeId>\n";
}
echo "</favorite>";
?>