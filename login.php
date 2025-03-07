<?php
session_start();
require_once 'config/config.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

$error_message = '';

// Controllo se l'utente è già loggato
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

// Processo di login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);
    
    if (loginUser($email, $password)) {
        header("Location: index.php");
        exit;
    } else {
        $error_message = 'Email o password non validi.';
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_NAME; ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@4.0.0/dist/tailwind.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white shadow-lg rounded-lg p-8">
            <div class="text-center mb-6">
                <img src="assets/img/logo.png" alt="Agenzia Servizi" class="mx-auto w-24 h-24">
                <h1 class="text-2xl font-bold mt-4">Agenzia Servizi</h1>
            </div>
            
            <?php if (!empty($error_message)): ?>
                <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="">
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" class="w-full px-3 py-2 border rounded" id="email" name="email" required>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-700">Password</label>
                    <input type="password" class="w-full px-3 py-2 border rounded" id="password" name="password" required>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-blue-600">
                        <label for="remember" class="ml-2 text-gray-700">Ricordami</label>
                    </div>
                    <a href="#" class="text-blue-600 hover:underline">Password dimenticata?</a>
                </div>
                <div class="mb-4">
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Accedi</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
