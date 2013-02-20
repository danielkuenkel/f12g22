<?php

# Autor: Daniel Malkmus
# Datum: 17.11.2012
# Die Klasse dbServerData stellt alle Serverdaten als Variable zur Verfügungdie
# werden später abgerufen.


class dbServerData {
# Selektiert der Server
    public static function getServer() {
        return 'sfsuswe.com';
    }
# Gibt den Usernamen zurück
    public static function getUsername() {
        return 'f12g22';
    }
# Gibt das Passwort zurück
    public static function getPassword() {
        return 'cookingdb';
    }
# Selektiert die Datenbank
    public static function getSelectdb() {
        return 'student_f12g22';
    }

}

?>
