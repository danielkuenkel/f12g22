<?php

# Autor: Anne, Molt
# Datum: 29.11.2012
# PHP  Datei zum Abrufen aus der Datenbank die Recipe, und liefert über 
# Xml zurück.


include 'dbOperations.php';

# Funktionsbeschreibung 
# Diese Funktion stellt eine Verbindung zur Datenbank her und führt die 
# übergebene sql-Anweisung durch
# Eingabewerte: (String) $selectString - sql-Anweisung, die durchgeführt
#                                        werden soll
# Rückgabewert: return: (sql-Result) $result - Ergebnis der DB abfrage


$query="SELECT unit_name, unit_id FROM unit";
$result=dbRequest($query);
header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<options>\n";
while($row = mysql_fetch_object($result))
{
  echo "<option>\n";
  echo "<id>$row->unit_id</id>\n";
  echo "<unitName>$row->unit_name</unitName>\n";
  echo "</option>\n";
}

echo "</options>\n";
?> 
