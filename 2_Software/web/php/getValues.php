<?php


# Autor: Anne, Molt
# Datum: 25.11.2012
# Hier wird Daten aus der Datenbank abgerufen und werden als xml angezeigt.





include 'dbOperations.php';

# Funktionsbeschreibung 
# Diese Funktion stellt eine Verbindung zur Datenbank her und führt die 
# übergebene sql-Anweisung durch

$id = $_GET['recipeId'];
$query="SELECT * FROM recipe WHERE recipe_id = $id";
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
