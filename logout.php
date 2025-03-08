<?php
// Avvia la sessione se non è già stata avviata
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pulisci tutte le variabili di sessione
$_SESSION = array();

// Distruggi la sessione
session_destroy();

// Reindirizza alla pagina di login
header("Location: login.php");
exit;
?>
