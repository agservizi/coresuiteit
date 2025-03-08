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

// Gestione form di aggiornamento password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_password') {
    $password_attuale = sanitizeInput($_POST['password_attuale']);
    $nuova_password = sanitizeInput($_POST['nuova_password']);
    $conferma_password = sanitizeInput($_POST['conferma_password']);
    
    if ($nuova_password !== $conferma_password) {
        $error_message = "Le nuove password non coincidono.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT password FROM utenti WHERE id = :id LIMIT 1");
            $stmt->bindParam(':id', $_SESSION['user_id']);
            $stmt->execute();
            $utente = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (password_verify($password_attuale, $utente['password'])) {
                $hashed_password = password_hash($nuova_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE utenti SET password = :password WHERE id = :id");
                $stmt->bindParam(':password', $hashed_password);
                $stmt->bindParam(':id', $_SESSION['user_id']);
                
                if ($stmt->execute()) {
                    $success_message = "Password aggiornata con successo!";
                } else {
                    $error_message = "Errore durante l'aggiornamento della password.";
                }
            } else {
                $error_message = "La password attuale non è corretta.";
            }
        } catch(PDOException $e) {
            $error_message = "Errore database: " . $e->getMessage();
        }
    }
}
?>

<div class="container mx-auto">
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
            <h2 class="card-title">Impostazioni Utente</h2>
            <form method="post" action="">
                <input type="hidden" name="action" value="update_password">
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Password Attuale <span class="text-error">*</span></span>
                    </label>
                    <input type="password" name="password_attuale" class="input input-bordered" required>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Nuova Password <span class="text-error">*</span></span>
                    </label>
                    <input type="password" name="nuova_password" class="input input-bordered" required>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Conferma Nuova Password <span class="text-error">*</span></span>
                    </label>
                    <input type="password" name="conferma_password" class="input input-bordered" required>
                </div>
                
                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">Aggiorna Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
