<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

// Controllo permessi
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$success_message = '';
$error_message = '';

// Gestione form di personalizzazione
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'personalizza') {
    $tema = sanitizeInput($_POST['tema']);
    $lingua = sanitizeInput($_POST['lingua']);
    
    // Salva le preferenze dell'utente (es. in sessione o database)
    $_SESSION['tema'] = $tema;
    $_SESSION['lingua'] = $lingua;
    
    $success_message = "Preferenze salvate con successo!";
}

// Ottieni le preferenze attuali
$tema_attuale = $_SESSION['tema'] ?? 'light';
$lingua_attuale = $_SESSION['lingua'] ?? 'it';
?>

<div class="container mx-auto">
    <h2>Personalizza l'Interfaccia</h2>
    
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success mb-4">
            <i class="fas fa-check-circle"></i>
            <span><?php echo $success_message; ?></span>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-error mb-4">
            <i class="fas fa-exclamation-circle"></i>
            <span><?php echo $error_message; ?></span>
        </div>
    <?php endif; ?>
    
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <form method="post" action="">
                <input type="hidden" name="action" value="personalizza">
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Tema</span>
                    </label>
                    <select name="tema" class="select select-bordered">
                        <option value="light" <?php echo ($tema_attuale == 'light') ? 'selected' : ''; ?>>Chiaro</option>
                        <option value="dark" <?php echo ($tema_attuale == 'dark') ? 'selected' : ''; ?>>Scuro</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Lingua</span>
                    </label>
                    <select name="lingua" class="select select-bordered">
                        <option value="it" <?php echo ($lingua_attuale == 'it') ? 'selected' : ''; ?>>Italiano</option>
                        <option value="en" <?php echo ($lingua_attuale == 'en') ? 'selected' : ''; ?>>Inglese</option>
                    </select>
                </div>
                
                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">Salva Preferenze</button>
                </div>
            </form>
        </div>
    </div>
</div>
