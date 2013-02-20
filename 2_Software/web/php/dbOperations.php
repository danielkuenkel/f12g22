<?php

# Autor: Malkmus, Daniel
# Datum: 16.11.2012
# Operationen auf MYSQL-Datenbank, DatenbankOperationen können dadurch durchgeführt


include 'dbServerData.php';
# Diese Funktion stellt eine Verbindung zur Datenbank her und führt die 
# übergebene sql-Anweisung durch
# Eingabewerte: (String) $selectString - sql-Anweisung, die durchgeführt
# werden soll
# Rückgabewert: return: (sql-Result) $result - Ergebnis der DB abfrage

function dbRequest($selectString) {

    //Open connection to MYSQL server
    mysql_connect(dbServerData::getServer(), dbServerData::getUsername(), dbServerData::getPassword());

    //Connect to DB
    mysql_select_db(dbServerData::getSelectdb());

    //Send request to query
    $result = mysql_query($selectString);
    return $result;
}

# Funktionsbeschreibung 
# Diese Funktion stellt eine Verbindung zur Datenbank her und führt ein Update
# anhand der übergebenen sql-Anweisung durch
# Eingabewerte: (String) $set - sql-Anweisung, die durchgeführt werden soll

function dbUpdate($set) {
    //Open connection to MYSQL server
    mysql_connect(dbServerData::getServer(), dbServerData::getUsername(), dbServerData::getPassword());

    //Connect to DB
    mysql_select_db(dbServerData::getSelectdb());

    //Send update string to query
    return mysql_query($set);
}

# Funktionsbeschreibung 
# Diese Funktion stellt eine Verbindung zur Datenbank her und löscht anhand der
#  übergebenen sql-Anweisung die Datensätze
# Eingabewerte: (String) $del - sql-Anweisung, die durchgeführt werden soll

function dbDelete($del) {
    # Open connection to MYSQL server
    mysql_connect(dbServerData::getServer(), dbServerData::getUsername(), dbServerData::getPassword());

    # Connect to DB
    mysql_select_db(dbServerData::getSelectdb());

    # Send delete String to query
    mysql_query($del);
}

?>
