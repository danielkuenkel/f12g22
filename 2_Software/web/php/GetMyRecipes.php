<?php

# Autor: Daniel Kuenkel
# Datum: 19.11.2012
#              
# Hier wird als php Script die Recipe aus der Datenbank abgerufen und
# dann als Xml und Bildern zurückgeliefert.

session_start();
include 'dbOperations.php';
 
# Funktionsbeschreibung 
# Diese Funktion stellt eine Verbindung zur Datenbank her und führt die 
# übergebene sql-Anweisung durch
# Eingabewerte: (String) $selectString - sql-Anweisung, die durchgeführt
#                                       werden soll
# Rückgabewert: return: (sql-Result) $result - Ergebnis der DB abfrage

$userid = $_SESSION['user_id'];
$query = "SELECT * FROM recipe WHERE user_id='$userid' ORDER BY voting DESC";
$result = dbRequest($query);

header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<recipes>\n";
while ($row = mysql_fetch_object($result)) {
    $recipeId = $row->recipe_id;
    $query = "SELECT * FROM comment WHERE recipe_id=$recipeId";
    $commentResult = dbRequest($query);
    $commentCount = mysql_num_rows($commentResult);
    $iconUrl = $row->icon_url;
    if (is_null($iconUrl)) {
        $iconUrl = "default";
    }
    $favoriteQuery = "SELECT * FROM favorites WHERE recipe_id=$recipeId";
    $favoriteResult = dbRequest($favoriteQuery);
    $favoriteCount = mysql_num_rows($favoriteResult);
    echo "<recipe>\n";
    echo "<id>$recipeId</id>\n";
    echo "<title>$row->title</title>\n";
    echo "<voting>$row->voting</voting>\n";
    echo "<comments>$commentCount</comments>\n";
    echo "<favorites>$favoriteCount</favorites>\n";
    echo "<url>$iconUrl</url>\n";
    echo "</recipe>\n";
}
echo "</recipes>";
?>