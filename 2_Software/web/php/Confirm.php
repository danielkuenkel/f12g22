<?php


# Autor: Daniel Kuenkel
# Datum: 17.11.2012
#              
# Diese Datei realisiert die Aktivierung eines Kontos beim Schreiben in der Daten
# Bank die ensprechende Angabe des Benutzer



include 'dbOperations.php';
$hash = $_GET['hash'];
$logonName = $_GET['logon'];
$query = "SELECT * FROM user WHERE logon_name='$logonName'";
$result = dbRequest($query);
$count = mysql_num_rows($result);
header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
echo "<activation>\n";
if ($result && $count == 1) {
    $row = mysql_fetch_object($result);
    if ($row->activate == 1) {
        echo "<activate>true</activate>\n";
        echo "<activateExists>true</activateExists>\n";
    } else {
        $equalHash = md5($row->user_id . $logonName . $row->password);
        if ($equalHash == $hash) {
            $query = "UPDATE user SET activate=1 WHERE logon_name='$logonName'";
            dbUpdate($query);
            echo "<activate>true</activate>\n";
            echo "<activateExists>false</activateExists>\n";
        } else {
            echo "<activate>false</activate>\n";
        }
    }
} else {
    echo "<activate>false</activate>\n";
}
echo "</activation>";
?>