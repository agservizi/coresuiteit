<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
require_once '../includes/api-keys.php';
require_once '../includes/auth.php';

// Controllo permessi
if (!isLoggedIn() || !hasRole('admin')) {
    header("Location: login.php");
    exit;
}

$success_message = '';
$error_message = '';

// Gestione generazione nuova API key
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'generate') {
    $user_id = sanitizeInput($_POST['user_id']);
    
    $api_key = generateApiKey($user_id);
    
    if ($api_key) {
        $success_message = "API Key generata con successo: " . $api_key;
    } else {
        $error_message = "Errore durante la generazione della API Key.";
    }
}

// Query per ottenere gli utenti per il form
try {
    $stmt = $conn->query("SELECT id, nome, cognome FROM utenti ORDER BY cognome, nome");
    $utenti = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $utenti = [];
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
    
    <!-- Card per generare nuova API key -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title">Genera Nuova API Key</h2>
            <form method="post" action="">
                <input type="hidden" name="action" value="generate">
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Utente <span class="text-error">*</span></span>
                    </label>
                    <select name="user_id" class="select select-bordered" required>
                        <option value="">-- Seleziona Utente --</option>
                        <?php foreach ($utenti as $utente): ?>
                            <option value="<?php echo $utente['id']; ?>">
                                <?php echo $utente['cognome'] . ' ' . $utente['nome']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">Genera API Key</button>
                </div>
            </form>
        </div>
    </div>
</div>
