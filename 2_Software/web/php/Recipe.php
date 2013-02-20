<?php

# Autor: Daniel Kuenkel
# Datum: 17.11.2012
#              
# Gibt alle Daten eines Rezeptes als XML zurück.

session_start();
include 'dbOperations.php';



$recipeId = $_GET['recipeId'];
$query = "SELECT * FROM recipe WHERE recipe_id=$recipeId";
$result = dbRequest($query);
if (!$result) {
    echo "Unknow Error";
    exit;
}
header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<recipes>\n";
if (mysql_num_rows($result) == 1) {
    $row = mysql_fetch_object($result);
    $userId = $row->user_id;
    $sessionUser = $_SESSION['user_id'];
    $isOwner = strcmp($userId, $sessionUser);
    $nameQuery = "SELECT * FROM user WHERE user_id=$userId";
    $nameResult = dbRequest($nameQuery);
    $logonName = mysql_fetch_object($nameResult);
    $ingredientQuery = "SELECT * FROM ingredient WHERE recipe_id=$row->recipe_id";
    $ingredientResult = dbRequest($ingredientQuery);
    $favoriteQuery = "SELECT * FROM favorites WHERE user_id=$sessionUser AND recipe_id=$recipeId";
    $favoriteResult = dbRequest($favoriteQuery);
    $isFavorite = 0;
    if ($favoriteResult) {
        $favoriteCount = mysql_num_rows($favoriteResult);
        $isFavorite = $favoriteCount;
    }
    echo "<recipe>\n";
    echo "<id>$row->recipe_id</id>\n";
    echo "<title>$row->title</title>\n";
    $imageUrl = $row->image_url;
    if (is_null($imageUrl)) {
        $imageUrl = "default";
    }
    echo "<url>$imageUrl</url>\n";
    echo "<user_id>$userId</user_id>\n";
    echo "<logon_name>$logonName->logon_name</logon_name>\n";
    echo "<isOwner>$isOwner</isOwner>\n";
    echo "<isFavorite>$isFavorite</isFavorite>\n";
    $abstract = " ";
    if (!is_null($row->abstract)) {
        $abstract = $row->abstract;
    }
    echo "<abstract>$abstract</abstract>\n";
    echo "<voting>$row->voting</voting>\n";
    echo "<votes>$row->total_votes</votes>\n";

    $voteQuery = "SELECT * FROM assessment WHERE user_id=$sessionUser AND recipe_id=$recipeId";
    $voteResult = dbRequest($voteQuery);
    $voteCount = mysql_num_rows($voteResult);
    $hasVoted = $voteCount == 0 ? 0 : 1;
    echo "<hasVoted>$hasVoted</hasVoted>\n";
    echo "<servings>$row->servings</servings>\n";
    echo "<preparation>$row->preparation</preparation>\n";
    echo "<cookingTime>$row->cooking_time</cookingTime>\n";
    echo "<videoUrl>$row->video_url</videoUrl>\n";
    echo"<ingredients>\n";
    while ($ingredientRow = mysql_fetch_object($ingredientResult)) {
        $quantity = is_null($ingredientRow->quantity) ? "NULL" : $ingredientRow->quantity;
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
    echo"<categories>\n";
    $categoryQuery = "SELECT name FROM category WHERE recipe_id=$recipeId";
    $categoryResult = dbRequest($categoryQuery);
    while ($catRow = mysql_fetch_object($categoryResult)) {
        echo "<category>\n";
        echo "<name>$catRow->name</name>\n";
        echo "</category>\n";
    }
    echo"</categories>\n";
    echo "</recipe>\n";
}
echo "</recipes>";
?>
