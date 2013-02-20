<?php


# Autor: Daniel Kuenkel
# Datum: 19.11.2012
#              
# Hier wird als php Script die Funktion "Kommentar löschen" implementiert

include 'dbOperations.php';
$recipeId = $_GET['recipeId'];
$commentId = $_GET['commentId'];
$query = "DELETE FROM comment WHERE comment_id=$commentId";
header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<comments>\n";
$result = dbUpdate($query);
if (!$result) {
    echo "<status>error</status>\n";
} else {
    echo "<status>okay</status>\n";
    echo "<recipeId>$recipeId</recipeId>\n";
}
echo "</comments>";
?>