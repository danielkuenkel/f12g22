<?php

# Autor: Daniel Kuenkel
# Datum: 21.12.2012
#              
# Gibt Daten eines/mehrerer Events als XML zurück.

session_start();
include 'dbOperations.php';

$searchType = $_GET['type'];
$zipcode = $_GET['zipcode'];

switch ($searchType) {
    case 'all':
        if ($zipcode != "") {
            $query = "SELECT * FROM event WHERE CAST(zipcode as CHAR) LIKE '%$zipcode%' ORDER BY timestamp ASC";
        } else {
            $query = "SELECT * FROM event ORDER BY timestamp ASC";
        }
        break;
    case 'my':
        $user_id = $_SESSION['user_id'];
        if ($zipcode != "") {
            $query = "SELECT * FROM event WHERE user_id = $user_id AND CAST(zipcode as CHAR) LIKE '%$zipcode%' ORDER BY timestamp ASC";
        } else {
            $query = "SELECT * FROM event WHERE user_id = $user_id ORDER BY timestamp ASC";
        }
        break;
    case 'joined':
        $user_id = $_SESSION['user_id'];
        if ($zipcode != "") {
            $query = "SELECT * FROM event WHERE event_id IN (SELECT event_id FROM event_participant WHERE user_id = $user_id) AND user_id != $user_id AND CAST(zipcode as CHAR) LIKE '%$zipcode%' ORDER BY timestamp ASC";
        } else {
            $query = "SELECT * FROM event WHERE event_id IN (SELECT event_id FROM event_participant WHERE user_id = $user_id) AND user_id != $user_id ORDER BY timestamp ASC";
        }
        break;
    default:
        $query = "SELECT * FROM event ORDER BY timestamp ASC";
        break;
}
$result = dbRequest($query);
if (!$result) {
    echo "Unknow Error";
    exit;
}
header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<events>\n";
while ($row = mysql_fetch_object($result)) {
    $userId = $row->user_id;
    $sessionUser = $_SESSION['user_id'];
    if ($sessionUser == null) {
        //Use the userId of the user which is loged on instead of the
        //UserId out of the Session
        $sessionUser = $_GET['sessionUser'];
    }
    $isOwner = $userId == $sessionUser ? 0 : 1;
    $nameQuery = "SELECT * FROM user WHERE user_id=$userId";
    $nameResult = dbRequest($nameQuery);
    $logonName = mysql_fetch_object($nameResult);

    echo "<event>\n";
    echo "<id>$row->event_id</id>\n";
    echo "<title>$row->title</title>\n";
    echo "<user_id>$userId</user_id>\n";
    echo "<logon_name>$logonName->logon_name</logon_name>\n";
    echo "<isOwner>$isOwner</isOwner>\n";
    echo "<abstract>$row->abstract</abstract>\n";
    echo "<maxParticipants>$row->max_participants</maxParticipants>\n";
    echo "<cost>$row->cost</cost>\n";
    echo "<street>$row->street</street>\n";
    echo "<houseNumber>$row->house_number</houseNumber>\n";
    echo "<zip>$row->zipcode</zip>\n";
    echo "<city>$row->city</city>\n";
    echo "<timestamp>$row->timestamp</timestamp>\n";
    echo "<participants>\n";
    $participantQuery = "SELECT * FROM user WHERE user_id IN (SELECT user_id FROM event_participant WHERE event_id = $row->event_id)";
    $participantResult = dbRequest($participantQuery);
    while ($rowP = mysql_fetch_object($participantResult)) {
        echo "<participant>\n";
        echo "<name>$rowP->logon_name</name>\n";
        echo "</participant>\n";
    }
    echo "</participants>\n";
    echo "</event>\n";
}
echo "</events>";
?>
