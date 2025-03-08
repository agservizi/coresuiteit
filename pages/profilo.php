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

// Gestione form di aggiornamento profilo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $nome = sanitizeInput($_POST['nome']);
    $cognome = sanitizeInput($_POST['cognome']);
    $email = sanitizeInput($_POST['email']);
    
    try {
        $stmt = $conn->prepare("UPDATE utenti SET nome = :nome, cognome = :cognome, email = :email WHERE id = :id");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cognome', $cognome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            $_SESSION['user_name'] = $nome . ' ' . $cognome;
            $_SESSION['user_email'] = $email;
            $success_message = "Profilo aggiornato con successo!";
        } else {
            $error_message = "Errore durante l'aggiornamento del profilo.";
        }
    } catch(PDOException $e) {
        $error_message = "Errore database: " . $e->getMessage();
    }
}

// Query per ottenere i dati dell'utente
try {
    $stmt = $conn->prepare("SELECT nome, cognome, email FROM utenti WHERE id = :id LIMIT 1");
    $stmt->bindParam(':id', $_SESSION['user_id']);
    $stmt->execute();
    $utente = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error_message = "Errore caricamento profilo: " . $e->getMessage();
    $utente = [];
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
            <h2 class="card-title">Profilo Utente</h2>
            <form method="post" action="">
                <input type="hidden" name="action" value="update_profile">
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Nome <span class="text-error">*</span></span>
                    </label>
                    <input type="text" name="nome" class="input input-bordered" value="<?php echo $utente['nome']; ?>" required>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Cognome <span class="text-error">*</span></span>
                    </label>
                    <input type="text" name="cognome" class="input input-bordered" value="<?php echo $utente['cognome']; ?>" required>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Email <span class="text-error">*</span></span>
                    </label>
                    <input type="email" name="email" class="input input-bordered" value="<?php echo $utente['email']; ?>" required>
                </div>
                
                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">Aggiorna Profilo</button>
                </div>
            </form>
        </div>
    </div>
</div>
