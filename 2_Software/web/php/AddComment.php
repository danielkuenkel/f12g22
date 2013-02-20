<?php

session_start();
include 'dbOperations.php';
$recipeId = $_GET['recipeId'];
$commentText = $_GET['comment'];
$userId = $_SESSION['user_id'];
$query = "INSERT INTO comment (user_id, recipe_id, comment) VALUES ($userId, $recipeId, '$commentText')";
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
