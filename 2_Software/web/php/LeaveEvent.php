<?php

# Autor Daniel Kuenkel
# Datum 28.11.2012
# Co-Autoren: 
# Fügt einen Teilnehmer ein spezifisches Event hinzu

session_start();
include 'dbOperations.php';

$eventId = $_GET['eventId'];
if (!isset($_SESSION['user_id'])) {
    $userId = $_GET['userId'];
} else {
    $userId = $_SESSION['user_id'];
}

$eventquery = "DELETE FROM event_participant WHERE event_id=$eventId AND user_id=$userId";
$result = dbRequest($eventquery);

header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<event>\n";
if ($result) {
     // $eventId = mysql_insert_id();
    echo "<joined>0</joined>\n";
    echo "<eventId>$eventId</eventId>\n";
} else {
    echo "<joined>1</joined>\n";
}
echo "</event>";
?>
