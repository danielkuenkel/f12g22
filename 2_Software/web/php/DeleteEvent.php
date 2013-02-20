<?php

# Autor: Anne Moldt
# Datum: 28.12.2012
#              
#  Skript zum Löschen eines Events

include 'dbOperations.php';
 
$eventId = $_GET['eventId'];

$query = "DELETE FROM event WHERE event_id=$eventId";
header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<event>\n";
$result = dbUpdate($query);
if (!$result) {
    echo "<status>error</status>\n";
    echo "</event>";
    exit;
}
$participantQuery = "DELETE FROM event_participant WHERE event_id=$eventId";
$participantResult = dbUpdate($participantQuery);
if (!$participantResult) {
    echo "<status>error</status>\n";
    echo "</event>";
    exit;
}
else {
    echo "<status>okay</status>\n";
    echo "<eventId>$eventId</eventId>\n";
}
echo "</event>";
?>
