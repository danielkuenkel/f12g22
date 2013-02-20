<?php
# Autor: Daniel Kuenkel
# Datum: 17.11.2012
#              
# Diese Datei Startet und endet einer Session.


session_start();
if (isset($_SESSION['logon_name'])) {
    $_SESSION['loggedIn'] = 1;
} else {
    $_SESSION['loggedIn'] = 0;
}
?>