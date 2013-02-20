<?php


# Autor: Daniel Kuenkel
# Datum: 19.11.2012
#              
# Diese Datei fügt ein Rezept in Favoriten hinzu 



session_start();
include 'dbOperations.php';
$recipeId = $_GET['recipeId'];
$userId = $_SESSION['user_id'];
$query = "INSERT INTO favorites (user_id, recipe_id) VALUES ($userId, $recipeId)";
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