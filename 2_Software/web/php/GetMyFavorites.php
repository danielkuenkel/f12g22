<?php


# Autor: Daniel Kuenkel
# Datum: 19.11.2012
#              
# Hier wird als php Script die Favoriten Speisen aus der Datenbank abgerufen, 
# dannach mit Titel, Voting, Comment angezeigt.


session_start();
include 'dbOperations.php';
$userid = $_SESSION['user_id'];
$query = "SELECT * FROM favorites WHERE user_id='$userid'";
$result = dbRequest($query);
header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<recipes>\n";
if (!$result) {
    echo "</recipes>";
}
while ($favoriteRow = mysql_fetch_object($result)) {
    $recipeQuery = "SELECT * FROM recipe WHERE recipe_id='$favoriteRow->recipe_id'";
    $recipeResult = dbRequest($recipeQuery);
    while ($row = mysql_fetch_object($recipeResult)) {
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
}
echo "</recipes>";
?>