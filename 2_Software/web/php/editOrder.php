<?php

# Autor: Anne,Molt
# Datum: 29.11.2012
# PHP Datei zum Starten des Editieren von Rezepten bzw. Ingredients aus der
# Datenbank



session_start();
include 'dbOperations.php';
# Funktionsbeschreibung 
# Diese Funktion stellt eine Verbindung zur Datenbank her und führt die 
# übergebene sql-Anweisung durch
# Eingabewerte: (String) $selectString - sql-Anweisung, $Get

$recipeId = $_GET['recipeId'];
$to = $_GET['to'];
$from = $_GET['from'];
$query = "SELECT * from ingredient WHERE recipe_id = $recipeId";
$result = dbRequest($query);
echo"<ingredients>\n";
while ($ingredientRow = mysql_fetch_object($result)) {
    $quantity = is_null($ingredientRow->quantity) ? "NULL" : $ingredientRow->quantity;
    if($quantity != "NULL")
    {
        $quantity = $quantity / $from * $to;
    }
    $ingedientName = $ingredientRow->ingredient;
    $unit = "NULL";
    if (!is_null($ingredientRow->unit_id)) {
        $unitQuery = "SELECT * FROM unit WHERE unit_id=$ingredientRow->unit_id";
        $unitResult = dbRequest($unitQuery);
        $unitName = mysql_fetch_object($unitResult);
        $unit = $unitName->unit_name;
    }
    echo "<ingredient>\n";
    echo "<unitId>$ingredientRow->unit_id</unitId>\n";
    echo "<quantity>$quantity</quantity>\n";
    echo "<unit>$unit</unit>\n";
    echo "<name>$ingedientName</name>\n";
    echo "</ingredient>\n";
}
echo"</ingredients>\n";
?>
