<?php
// Avvia la sessione all'inizio di ogni pagina
session_start();

// Configurazione database
define('DB_HOST', 'localhost');
define('DB_NAME', 'u427445037_coresuite');
define('DB_USER', 'u427445037_coresuite');
define('DB_PASS', 'password');

// Altre configurazioni
define('SITE_NAME', 'CoreSuite IT');
define('SITE_URL', 'https://coresuite.it');
define('ADMIN_EMAIL', 'admin@coresuite.it');

// Timezone
date_default_timezone_set('Europe/Rome');

// Configurazione errori (disattivare in produzione)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connessione al database
try {
    $conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET NAMES utf8");
} catch(PDOException $e) {
    die("Errore di connessione al database: " . $e->getMessage());
}

// Includi funzioni globali
require_once __DIR__ . '/../includes/functions.php';
?>
