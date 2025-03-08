<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

// Controllo permessi
if (!isLoggedIn()) {
    header("HTTP/1.1 403 Forbidden");
    echo "Accesso negato";
    exit;
}

// Controllo parametri
if (!isset($_GET['cliente_id']) || !isset($_GET['filename'])) {
    header("HTTP/1.1 400 Bad Request");
    echo "Parametri mancanti";
    exit;
}

$cliente_id = sanitizeInput($_GET['cliente_id']);
$filename = sanitizeInput($_GET['filename']);
$filepath = "../uploads/cliente_" . $cliente_id . "/" . $filename;

// Controlla se il file esiste
if (!file_exists($filepath)) {
    header("HTTP/1.1 404 Not Found");
    echo "File non trovato";
    exit;
}

// Controlla se l'utente ha i permessi (admin può accedere a tutti i file, operatore solo a quelli suoi)
if (!hasRole('admin')) {
    // Implementare controlli di accesso specifici se necessario
}

// Ottiene il tipo MIME del file
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime_type = finfo_file($finfo, $filepath);
finfo_close($finfo);

// Imposta gli header HTTP per il download
header("Content-Type: " . $mime_type);
header("Content-Disposition: attachment; filename=\"" . basename($filename) . "\"");
header("Content-Length: " . filesize($filepath));
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Invia il file al client
readfile($filepath);
exit;
