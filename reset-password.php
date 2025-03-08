<?php
require_once 'config/config.php';
require_once 'includes/functions.php';
require_once 'includes/email.php';

$step = 'form';
$message = '';
$error = '';
$token = '';
$user_id = null;

// Se l'utente è già loggato, reindirizza alla dashboard
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

// Verifica se il token è presente
if (isset($_GET['token'])) {
    $token = sanitizeInput($_GET['token']);
    $user_id = verificaTokenRecupero($token);
    
    if (!$user_id) {
        $error = "Token non valido o scaduto. Richiedi nuovamente il recupero password.";
        $step = 'error';
    }
} else {
    $error = "Token non valido. Richiedi nuovamente il recupero password.";
    $step = 'error';
}

// Gestione reset password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reset') {
    $password = sanitizeInput($_POST['password']);
    $conferma_password = sanitizeInput($_POST['conferma_password']);
    
    if ($password !== $conferma_password) {
        $error = "Le password non coincidono.";
    } elseif (strlen($password) < 8) {
        $error = "La password deve essere lunga almeno 8 caratteri.";
    } else {
        if (resetPassword($user_id, $password, $token)) {
            $message = "Password reimpostata con successo! Puoi effettuare il login.";
            $step = 'success';
        } else {
            $error = "Si è verificato un errore durante il reset della password. Riprova più tardi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reimposta Password - <?php echo SITE_NAME; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: url('assets/img/login-bg.jpg') center center/cover no-repeat fixed;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .reset-card {
            max-width: 500px;
            width: 100%;
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.8);
        }
        .reset-logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card reset-card shadow-lg mx-auto">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <img src="assets/img/logo.png" alt="CoreSuite Logo" class="reset-logo">
                    <h3 class="card-title">Reimposta la tua password</h3>
                    <p class="text-muted">Inserisci la nuova password</p>
                </div>
                
                <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($message)): ?>
                <div class="alert alert-success">
                    <?php echo $message; ?>
                </div>
                <?php endif; ?>
                
                <?php if ($step === 'form'): ?>
                <form method="post" action="" class="mt-4">
                    <input type="hidden" name="action" value="reset">
                    <div class="mb-3">
                        <label for="password" class="form-label">Nuova Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="conferma_password" class="form-label">Conferma Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="conferma_password" name="conferma_password" required>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-block py-2">Reimposta Password</button>
                    </div>
                </form>
                <?php endif; ?>
                
                <?php if ($step === 'success'): ?>
                <div class="text-center mt-4">
                    <p>Password reimpostata con successo! <a href="login.php">Accedi ora</a></p>
                </div>
                <?php endif; ?>
                
                <?php if ($step === 'error'): ?>
                <div class="text-center mt-4">
                    <p><?php echo $error; ?></p>
                    <a href="password-recovery.php">Recupera Password</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="text-center mt-3 text-white">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Tutti i diritti riservati.</p>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
