<?php


# Autor: Daniel Kuenkel
# Datum: 17.11.2012
#              
# Diese Datei ruft die Rezepte aus der Datenbank ab und dann übergibt sie als 
# XML und Bildern.



include 'dbOperations.php';
$searchKeyword = $_GET['search'];
$splitKeys = explode(" ", $searchKeyword);
$query = "SELECT * FROM recipe WHERE ";
$count = count($splitKeys);
$i = 0;
foreach ($splitKeys as $value) {
    if ($i < $count - 1) {
        $query .= "title LIKE '%$value%' OR abstract LIKE '%$value%' OR preparation LIKE '%$value%' OR ";
    } else {
        $query .= "title LIKE '%$value%' OR abstract LIKE '%$value%' OR preparation LIKE '%$value%'";
    }
    $i++;
}
$query .= " ORDER BY voting DESC";
$result = dbRequest($query);

header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<recipes>\n";
while ($row = mysql_fetch_object($result)) {
    $recipeId = $row->recipe_id;
    $commentQuery = "SELECT * FROM comment WHERE recipe_id=$recipeId";
    $commentResult = dbRequest($commentQuery);
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