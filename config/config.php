<?php
// Avvia la sessione solo se non è già stata avviata
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configurazione database
define('DB_HOST', '127.0.0.1:3306');
define('DB_NAME', 'u427445037_coresuite');
define('DB_USER', 'u427445037_coresuite');  // Tornato all'utente originale
define('DB_PASS', 'Giogiu2123@');  // Tornato alla password originale

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

// Includi funzioni globali se il file esiste
$functions_path = __DIR__ . '/../includes/functions.php';
if (file_exists($functions_path)) {
    require_once $functions_path;
}
?>
