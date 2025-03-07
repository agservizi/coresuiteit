<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once 'config/config.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

// Verifica se l'utente è loggato, altrimenti reindirizza alla pagina di login
if (!isLoggedIn() && basename($_SERVER['PHP_SELF']) != 'login.php') {
    header("Location: login.php");
    exit;
}

// Gestione del routing di base
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$allowed_pages = ['dashboard', 'pagamenti', 'telefonia', 'energia', 'spedizioni', 'servizi-digitali', 'clienti', 'utenti'];

if (in_array($page, $allowed_pages)) {
    $page_to_load = "pages/{$page}.php";
} else {
    $page_to_load = "pages/dashboard.php";
}

// Include l'header, la pagina richiesta e il footer
include 'includes/header.php';
include $page_to_load;
include 'includes/footer.php';
?>
