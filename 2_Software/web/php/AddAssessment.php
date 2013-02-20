<?php

# Autor: Daniel Kuenkel
# Datum: 17.11.2012
#              
# Diese Datei dienst zur Bewertung von Rezepten.



session_start();

# ruft die Datenbank ab über sql request und liest die dadrin stehende Daten

include 'dbOperations.php';
$recipeId = $_GET['recipeId'];
$voting = $_GET['assessment'];
$userId = $_SESSION['user_id'];
$query = "INSERT INTO assessment (user_id, recipe_id, voting) VALUES ($userId, $recipeId, $voting)";
$result = dbUpdate($query);
header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<recipes>\n";
echo "<recipe>\n";
if (!$result) {
    echo "<status>error1</status>\n";
    echo "</recipe>\n";
    echo "</recipes>";
    exit;
}
$votingQuery = "SELECT * FROM assessment WHERE recipe_id=$recipeId";
$votingResult = dbRequest($votingQuery);

if (!$votingResult) {
    echo "<status>error2</status>\n";
    echo "</recipe>\n";
    echo "</recipes>";
    exit;
} else {
    $totalVotes = mysql_num_rows($votingResult);
    $voting = 0;
    while ($votingRow = mysql_fetch_object($votingResult)) {
        $voting += $votingRow->voting;
    }
    $roundedVoting = round($voting / $totalVotes, 1);

    $insertQuery = "UPDATE recipe SET voting=$roundedVoting, total_votes=$totalVotes WHERE recipe_id=$recipeId";
    $insertResult = dbUpdate($insertQuery);

    if (!$insertResult) {
        echo "<status>error3</status>\n";
    } else {
        echo "<voting>$roundedVoting</voting>\n";
        echo "<votes>$totalVotes</votes>\n";
        echo "<hasVoted>1</hasVoted>\n";
        echo "<status>okay</status>\n";
        echo "<recipeId>$recipeId</recipeId>\n";
    }
}
echo "</recipe>\n";
echo "</recipes>";
?>



