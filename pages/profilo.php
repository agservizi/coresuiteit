<?php
require_once BASE_PATH . 'includes/functions.php';

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
                    <h5 class="mb-0">Profilo Utente</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="">
                        <input type="hidden" name="action" value="update_profile">
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="nome" class="form-label form-required">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $utente['nome']; ?>" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="cognome" class="form-label form-required">Cognome</label>
                                <input type="text" class="form-control" id="cognome" name="cognome" value="<?php echo $utente['cognome']; ?>" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="email" class="form-label form-required">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $utente['email']; ?>" required>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Aggiorna Profilo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
