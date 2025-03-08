<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

// Controllo permessi
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Gestione marcatura come letta
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'mark_read') {
    $notifica_id = sanitizeInput($_GET['id']);
    
    if (marcaNotificaLetta($notifica_id)) {
        header("Location: notifiche.php");
        exit;
    } else {
        $error_message = "Errore durante la marcatura della notifica come letta.";
    }
}

// Ottieni le notifiche dell'utente
$notifiche = getNotificheNonLette($_SESSION['user_id'], 20);
?>

<div class="container mx-auto">
    <h2>Notifiche</h2>
    
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-error mb-4">
            <i class="fas fa-exclamation-circle"></i>
            <span><?php echo $error_message; ?></span>
        </div>
    <?php endif; ?>
    
    <?php if (empty($notifiche)): ?>
        <p>Nessuna notifica non letta.</p>
    <?php else: ?>
        <ul class="list-group">
            <?php foreach ($notifiche as $notifica): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <?php echo $notifica['messaggio']; ?>
                        <br>
                        <small class="text-muted"><?php echo formatDate($notifica['data_creazione']); ?></small>
                    </div>
                    <div>
                        <a href="?action=mark_read&id=<?php echo $notifica['id']; ?>" class="btn btn-sm btn-success">
                            <i class="fas fa-check"></i> Segna come letta
                        </a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
