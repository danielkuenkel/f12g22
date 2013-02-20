<?php
session_start();
include 'dbOperations.php';

$eventId = $_GET['eventId'];
$query = "SELECT * FROM event WHERE event_id=$eventId";
$result = dbRequest($query);
if (!$result) {
    echo "Unknow Error";
    exit;
}
header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<events>\n";
if (mysql_num_rows($result) == 1) {
    $row = mysql_fetch_object($result);
    $userId = $row->user_id;
    $sessionUser = $_SESSION['user_id'];
    $isOwner = strcmp($userId, $sessionUser);

    $isParticipantQuery = "SELECT * FROM event_participant WHERE event_id=$eventId";
    $isParticipantResult = dbRequest($isParticipantQuery);
    $isParticipant = false;
    while ($isParticipantrow = mysql_fetch_object($isParticipantResult)) {
        if (strcmp($isParticipantrow->user_id, $sessionUser) == 0) {
            $isParticipant = true;
        }
    }

    //find name of owner
    $nameQuery = "SELECT * FROM user WHERE user_id=$userId";
    $nameResult = dbRequest($nameQuery);
    $logonName = mysql_fetch_object($nameResult);
    $time = getDate($row->timestamp);

    echo "<event>\n";
    echo "<id>$row->event_id</id>\n";
    echo "<title>$row->title</title>\n";
    echo "<user_id>$userId</user_id>\n";
    echo "<logon_name>$logonName->logon_name</logon_name>\n";
    if ($isParticipant == true) {
        echo"<isParticipant>1</isParticipant>\n";
    } else {
        echo"<isParticipant>0</isParticipant>\n";
    }
    echo "<isOwner>$isOwner</isOwner>\n";
    echo "<abstract>$row->abstract</abstract>\n";
    echo "<cost>$row->cost</cost>\n";
    echo "<street>$row->street</street>\n";
    echo "<houseNumber>$row->house_number</houseNumber>\n";
    echo "<zip>$row->zipcode</zip>\n";
    echo "<city>$row->city</city>\n";
    echo "<date>$time[mday]/$time[mon]/$time[year]</date>\n";
    echo "<timestamp>$time[mday]. $time[month] $time[year] at $time[hours]:$time[minutes]</timestamp>\n";
    echo "<hour>$time[hours]</hour>\n";
    echo "<minute>$time[minutes]</minute>\n";
    echo "<maxParticipants>$row->max_participants</maxParticipants>\n";

    //find participants and number of participants
    $participantsQuery = "SELECT logon_name FROM user WHERE user_id IN (SELECT user_id FROM event_participant WHERE event_id = $eventId)";
    $participantsResult = dbRequest($participantsQuery);

    $numberParticipants = 0;
    echo "<participants>\n";
    while ($partyrow = mysql_fetch_object($participantsResult)) {
        echo "<participant><name>$partyrow->logon_name</name></participant>\n";
        $numberParticipants++;
    }
    echo "</participants>\n";
    $freeplaces = $row->max_participants - $numberParticipants;
    echo "<freePlaces>$freeplaces</freePlaces>\n";
    echo "</event>";
}
echo "</events>";
?>
