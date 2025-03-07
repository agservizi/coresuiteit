<?php
// Configurazioni del Database
define('DB_HOST', '127.0.0.1:3306');
define('DB_USER', 'u427445037_coresuite'); // Cambia 'root' con il nome utente corretto
define('DB_PASS', 'Giogiu2123@'); // Inserisci la password corretta
define('DB_NAME', 'u427445037_coresuite');

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
