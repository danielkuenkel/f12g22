<?php

# Autor Anne Moldt
# Datum 21.11.2012
# Co-Autoren: Daniel Kuenkel
# Diese Datei speichert ein neues Event, indem die Angaben inder Datenbank hin
# zugefügt werden

session_start();
include 'dbOperations.php';

if (!isset($_SESSION['user_id'])) {
    $userId = $_GET['userId'];
} else {
    $userId = $_SESSION['user_id'];
}
$title = $_GET['eventTitle'];
$updateEventId = $_GET['updateEventId'];
$persons = $_GET['maxPersons'];
$eventStreet = $_GET['street'];
$houseNumber = $_GET['housenumber'];
$zipcode = $_GET['zipcode'];
$eventCity = $_GET['city'];
$description = $_GET['description'];
$hour = (float) $_GET['hour'];
$minute = (float) $_GET['minute'];
$date = explode("/", $_GET['date']);
$day = (float) $date[0];
$month = (float) $date[1];
$year = (float) $date[2];
$timestamp = mktime($hour, $minute, 0, $month, $day, $year, -1);

$cost = $_GET['cost'];
if (strpos($cost, '.') < strpos($cost, ',')) {
    $costStringTemp = str_replace('.', '', $cost);
    $costString = strtr($costStringTemp, ',', '.');
} else {
    $costString = str_replace(',', '', $cost);
}
$costFloat = (float) $costString;
//insert event
if ($updateEventId != "") {
    $eventId = $updateEventId;
    $eventquery = "UPDATE event SET title='$title', abstract='$description', max_participants=$persons, cost=$costFloat, street='$eventStreet', house_number=$houseNumber, zipcode='$zipcode', city='$eventCity', timestamp=$timestamp WHERE event_id=$updateEventId";
} else {
    $eventquery = "INSERT INTO event (user_id, title, abstract, max_participants, cost, street, house_number, zipcode, city, timestamp) VALUES ($userId, '$title', '$description', $persons, $costFloat, '$eventStreet', $houseNumber, '$zipcode', '$eventCity', $timestamp)";
}
$result = dbRequest($eventquery);

//find the last inserted event


//insert first participant for this event
if ($updateEventId == "") {
    $eventId = mysql_insert_id();
    
    $participantquery = "INSERT INTO event_participant VALUES ($eventId, $userId)";
    dbRequest($participantquery);
}

header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<events>\n";
echo "<event>\n";
if ($result) {     
    echo "<created>1</created>\n";
    echo "<eventId>$eventId</eventId>\n";
} else {
    echo "<created>0</created>\n";
}
echo "</event>\n";
echo "</events>";
?>
