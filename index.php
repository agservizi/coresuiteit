<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once 'config/config.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

// Se l'utente non è loggato, reindirizza alla pagina di login
if (!isLoggedIn() && !in_array(basename($_SERVER['PHP_SELF']), ['login.php', 'register.php'])) {
    header("Location: login.php");
    exit;
}

// Imposta il titolo della pagina
$pageTitle = 'Dashboard - ' . SITE_NAME;

// Carica la pagina richiesta o la dashboard di default
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Lista delle pagine consentite
$allowed_pages = [
    'dashboard',
    'clienti',
    'pagamenti',
    'telefonia',
    'energia',
    'spedizioni',
    'fatture',
    'servizi-digitali',
    'utenti',
    'profilo',
    'impostazioni'
];

// Verifica che la pagina richiesta sia consentita
if (!in_array($page, $allowed_pages)) {
    $page = 'dashboard';
}

// Verifica permessi per le pagine riservate agli admin
if ($page === 'utenti' && !hasRole('admin')) {
    $page = 'dashboard';
}

// Include l'header
require_once 'includes/header.php';

// Include la pagina richiesta
$page_path = 'pages/' . $page . '.php';
if (file_exists($page_path)) {
    include($page_path);
} else {
    echo "<div class='alert alert-danger'>La pagina richiesta non esiste.</div>";
}

// Include il footer
require_once 'includes/footer.php';
?>
