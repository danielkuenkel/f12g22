<?php


# Autor: Daniel Kuenkel
# Datum: 19.11.2012
#              
#  Hier wird als php Script die Funktion "Recipe löschen" implementiert




session_start();
include 'dbOperations.php';
 
# Funktionsbeschreibung 
# Diese Funktion stellt eine Verbindung zur Datenbank her und führt die 
# übergebene sql-Anweisung durch
# Eingabewerte: (String) $selectString - sql-Anweisung, die durchgeführt
#                                        werden soll
# Rückgabewert: return: (sql-Result) $result - Ergebnis der DB abfrage



$recipeId = $_GET['recipeId'];
$userId = $_SESSION['user_id'];
$query = "DELETE FROM recipe WHERE recipe_id=$recipeId";
header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<recipe>\n";
$result = dbUpdate($query);
if (!$result) {
    echo "<status>error</status>\n";
    echo "</recipe>";
    exit;
}
$commentQuery = "DELETE FROM comment WHERE recipe_id=$recipeId";
$commentResult = dbUpdate($commentQuery);
if (!$commentResult) {
    echo "<status>error</status>\n";
    echo "</recipe>";
    exit;
}
$ingredientQuery = "DELETE FROM ingredient WHERE recipe_id=$recipeId";
$ingredientResult = dbUpdate($ingredientQuery);
if (!$ingredientResult) {
    echo "<status>error</status>\n";
    echo "</recipe>";
    exit;
}
$favoriteQuery = "DELETE FROM favorites WHERE recipe_id=$recipeId AND user_id=$userId";
$favoriteResult = dbUpdate($favoriteQuery);
if (!$favoriteResult) {
    echo "<status>error</status>\n";
    echo "</recipe>";
    exit;
}
$catQuery = "DELETE FROM category WHERE recipe_id=$recipeId";
$catResult = dbUpdate($catQuery);
if (!$catResult) {
    echo "<status>error</status>\n";
    echo "</recipe>";
    exit;
}
$votingQuery = "DELETE FROM assessment WHERE recipe_id=$recipeId";
$votingResult = dbUpdate($votingQuery);
if (!$votingResult) {
    echo "<status>error</status>\n";
    echo "</recipe>";
    exit;
} else {
    echo "<status>okay</status>\n";
    echo "<recipeId>$recipeId</recipeId>\n";
}
echo "</recipe>";
?>