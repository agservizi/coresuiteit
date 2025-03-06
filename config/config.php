<?php
// Configurazioni del Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'agenzia_servizi');

// Connessione al Database
try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Errore di connessione al database: " . $e->getMessage());
}

// Configurazione del sito
define('SITE_NAME', 'Agenzia Servizi');
define('BASE_URL', 'http://localhost/coresuite/');

// Timezone
date_default_timezone_set('Europe/Rome');
?>
