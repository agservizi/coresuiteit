<?php
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

<div class="container-fluid">
    <?php if (!empty($success_message)): ?>
        <?php echo showAlert($success_message, 'success'); ?>
    <?php endif; ?>
    
    <?php if (!empty($error_message)): ?>
        <?php echo showAlert($error_message, 'danger'); ?>
    <?php endif; ?>
    
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Impostazioni Utente</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="">
                        <input type="hidden" name="action" value="update_password">
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="password_attuale" class="form-label form-required">Password Attuale</label>
                                <input type="password" class="form-control" id="password_attuale" name="password_attuale" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="nuova_password" class="form-label form-required">Nuova Password</label>
                                <input type="password" class="form-control" id="nuova_password" name="nuova_password" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="conferma_password" class="form-label form-required">Conferma Nuova Password</label>
                                <input type="password" class="form-control" id="conferma_password" name="conferma_password" required>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Aggiorna Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
