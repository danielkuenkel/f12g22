<?php

# Autor: Daniel Kuenkel
# Datum: 17.11.2012
#              
# Diese Datei implement die Suchfunktion per Schlüsselwort und dann übergibt die
# als Xml die gesuchte Rezepte.

include 'dbOperations.php';
$filterKeys = $_GET['filterKeys'];
$splitKeys = explode(",", $filterKeys);
$filterQuery = "SELECT COUNT(recipe_id) AS counter, recipe_id FROM category WHERE name IN(";
$count = count($splitKeys);
$i = 0;
foreach ($splitKeys as $value) {
    if ($i < $count - 1) {
        $filterQuery .= "'$value', ";
    } else {
        $filterQuery .= "'$value'";
    }
    $i++;
}
$filterQuery .= ") GROUP BY recipe_id";
$filterResult = dbRequest($filterQuery);

header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<recipes>\n";

while ($filterRow = mysql_fetch_object($filterResult)) {
    if($filterRow->counter == count($splitKeys)) {
        $query = "SELECT * FROM recipe WHERE recipe_id='$filterRow->recipe_id'";
        $result = dbRequest($query);
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
    }
}
echo "</recipes>";
?>