<?php

# Autor: Daniel Kuenkel
# Datum: 02.12.2012
#              
#  Hier wird als php Script das Rezeptanzeige implementiert 

include 'dbOperations.php';

# Funktionsbeschreibung 
# Diese Funktion stellt eine Verbindung zur Datenbank her und führt die 
# übergebene sql-Anweisung durch
# Eingabewerte: (String) $selectString - sql-Anweisung, die durchgeführt
#                                        werden soll
# Rückgabewert: return: (sql-Result) $result - Ergebnis der DB abfrage

 

$resultCount = dbRequest("SELECT count(*) FROM recipe");
$count = mysql_fetch_row($resultCount);
$random = mt_rand(0, $count[0] - 1);
$result = dbRequest("SELECT * FROM recipe LIMIT $random, 1");

if (!$result) {
    echo "Unknow Error";
    exit;
}
$row = mysql_fetch_object($result);
header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<recipes>\n";
echo "<recipe>\n";
echo "<id>$row->recipe_id</id>\n";
echo "<title>$row->title</title>\n";
$imageUrl = $row->icon_url;
if (is_null($imageUrl)) {
    $imageUrl = "default";
}
echo "<url>$imageUrl</url>\n";
echo "<abstract>$row->abstract</abstract>\n";
echo "<voting>$row->voting</voting>\n";
echo "<votes>$row->total_votes</votes>\n";
echo "<preparation>$row->preparation</preparation>\n";
echo "<cookingTime>$row->cooking_time</cookingTime>\n";
echo "</recipe>\n";
echo "</recipes>";
?>
