<?php


# Autor: Daniel Kuenkel
# Datum: 19.11.2012
#              
# Hier wird als php Script die Konversion von Zutaten je nach wieviel personnen 
# und Gericht Implementiert.



include 'dbOperations.php';
$recipeId = $_GET['recipeId'];
$to = $_GET['to'];
$from = $_GET['from'];
$query = "SELECT * FROM ingredient WHERE recipe_id=$recipeId";
$result = dbRequest($query);
header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<ingredients>\n";
if (!$result) {
    echo "</ingredients>\n";
    exit;
}
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
    echo "<quantity>$quantity</quantity>\n";
    echo "<unit>$unit</unit>\n";
    echo "<name>$ingedientName</name>\n";
    echo "</ingredient>\n";
}
echo"</ingredients>\n";
?>