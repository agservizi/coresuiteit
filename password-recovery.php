<?php
require_once 'config/config.php';
require_once 'includes/functions.php';
require_once 'includes/email.php';

$step = 'email';
$message = '';
$error = '';
$email = '';
$token = '';

// Se l'utente è già loggato, reindirizza alla dashboard
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

// Gestione richiesta di recupero password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'recover') {
    $email = sanitizeInput($_POST['email']);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Inserisci un indirizzo email valido.";
    } else {
        $token = generaTokenRecupero($email);
        
        if ($token) {
            // Invia email con link di recupero
            $reset_url = SITE_URL . "/reset-password.php?token=" . $token;
            $subject = "Recupero password - " . SITE_NAME;
            
            // Utilizza il template email
            $email_body = renderEmailTemplate('password_reset', ['resetLink' => $reset_url]);
            
            if (sendEmail($email, $subject, $email_body)) {
                $step = 'sent';
                $message = "Ti abbiamo inviato un'email con le istruzioni per reimpostare la tua password.";
            } else {
                $error = "Si è verificato un errore durante l'invio dell'email. Riprova più tardi.";
            }
        } else {
            $error = "Non è stato trovato alcun account con questo indirizzo email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recupero Password - <?php echo SITE_NAME; ?></title>
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
        .recovery-card {
            max-width: 500px;
            width: 100%;
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.8);
        }
        .recovery-logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card recovery-card shadow-lg mx-auto">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <img src="assets/img/logo.png" alt="CoreSuite Logo" class="recovery-logo">
                    <h3 class="card-title">Recupera la tua password</h3>
                    <p class="text-muted">Inserisci la tua email per iniziare</p>
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
                
                <?php if ($step === 'email'): ?>
                <form method="post" action="" class="mt-4">
                    <input type="hidden" name="action" value="recover">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-block py-2">Recupera Password</button>
                    </div>
                </form>
                <?php endif; ?>
                
                <?php if ($step === 'sent'): ?>
                <div class="text-center mt-4">
                    <p>Controlla la tua casella di posta. Ti abbiamo inviato un'email con le istruzioni per reimpostare la password.</p>
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
