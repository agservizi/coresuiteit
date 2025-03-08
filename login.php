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
    
    try {
        $stmt = $conn->prepare("SELECT * FROM utenti WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (password_verify($password, $user['password'])) {
                // Login riuscito, imposta la sessione
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nome'] . ' ' . $user['cognome'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['ruolo'];
                
                // Registra l'accesso
                $stmt = $conn->prepare("UPDATE utenti SET ultimo_accesso = NOW() WHERE id = :id");
                $stmt->bindParam(':id', $user['id']);
                $stmt->execute();
                
                // Reindirizza alla dashboard
                header("Location: index.php");
                exit;
            } else {
                $error_message = "Password non corretta. Riprova.";
            }
        } else {
            $error_message = "Nessun account trovato con questa email. Riprova o registrati.";
        }
    } catch (PDOException $e) {
        $error_message = "Si è verificato un errore. Riprova più tardi.";
    }
}
?>

<!DOCTYPE html>
<html lang="it" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_NAME; ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@4.0.0/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.0.0/dist/full.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="hero min-h-screen bg-base-200">
        <div class="hero-content flex-col">
            <div class="card w-full max-w-md shadow-2xl bg-base-100">
                <div class="card-body">
                    <div class="text-center mb-6">
                        <img src="assets/img/logo.png" alt="Agenzia Servizi" class="mx-auto w-24 h-24">
                        <h1 class="text-2xl font-bold mt-4">Agenzia Servizi</h1>
                    </div>
                    
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-error mb-4">
                            <i class="fas fa-exclamation-circle"></i>
                            <span><?php echo $error_message; ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post" action="">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Email</span>
                            </label>
                            <input type="email" id="email" name="email" class="input input-bordered" required>
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Password</span>
                            </label>
                            <input type="password" id="password" name="password" class="input input-bordered" required>
                            <label class="label">
                                <a href="#" class="label-text-alt link">Password dimenticata?</a>
                            </label>
                        </div>
                        <div class="form-control mt-4">
                            <label class="cursor-pointer label justify-start">
                                <input type="checkbox" id="remember" name="remember" class="checkbox checkbox-sm mr-2">
                                <span class="label-text">Ricordami</span>
                            </label>
                        </div>
                        <div class="form-control mt-6">
                            <button type="submit" class="btn btn-primary">Accedi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Check for saved theme preference
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                document.querySelector('html').setAttribute('data-theme', savedTheme);
            }
        });
    </script>
</body>
</html>
