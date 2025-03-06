<?php
session_start();
require_once BASE_PATH . 'includes/auth.php';

// Esegui la funzione di logout
logoutUser();
?>
