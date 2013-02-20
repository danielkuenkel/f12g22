<?php

# Autor: Daniel Kuenkel
# Datum: 19.11.2012
#              
# Hier wird als php Script die Kommentare aus der Datenbank abgerufen und
# dann als Xml angezeigt.

session_start();
include 'dbOperations.php';

$recipeId = $_GET['recipeId'];
$query = "SELECT * FROM comment WHERE recipe_id=$recipeId ORDER BY created DESC";
header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<comments>\n";
echo "<recipeId>$recipeId</recipeId>\n";
$result = dbRequest($query);
if (!$result) {
    echo "</comments>";
    exit;
} else {
    while ($row = mysql_fetch_object($result)) {
        $userId = $row->user_id;
        $nameQuery = "SELECT * FROM user WHERE user_id=$userId";
        $nameResult = dbRequest($nameQuery);
        $logonName = mysql_fetch_object($nameResult);
        $isOwner = strcmp($userId, $_SESSION['user_id']);
        $logon = "deleted person";
        if(is_object($logonName))
        {
            $logon = $logonName->logon_name;
        }
        echo "<comment>\n";
        echo "<commentId>$row->comment_id</commentId>\n";
        echo "<logon>$logon</logon>\n";
        echo "<timestamp>$row->created</timestamp>\n";
        echo "<copy>$row->comment</copy>\n";
        echo "<isOwner>$isOwner</isOwner>\n";
        echo "</comment>\n";
    }
}
echo "</comments>";
?>