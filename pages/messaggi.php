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

// Gestione invio nuovo messaggio
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send_message') {
    $destinatario_id = sanitizeInput($_POST['destinatario_id']);
    $messaggio = sanitizeInput($_POST['messaggio']);
    
    try {
        $stmt = $conn->prepare("INSERT INTO messaggi (mittente_id, destinatario_id, messaggio, data_invio, letto) 
                              VALUES (:mittente_id, :destinatario_id, :messaggio, NOW(), 0)");
        $stmt->bindParam(':mittente_id', $_SESSION['user_id']);
        $stmt->bindParam(':destinatario_id', $destinatario_id);
        $stmt->bindParam(':messaggio', $messaggio);
        
        if ($stmt->execute()) {
            $success_message = "Messaggio inviato con successo!";
        } else {
            $error_message = "Errore durante l'invio del messaggio.";
        }
    } catch(PDOException $e) {
        $error_message = "Errore database: " . $e->getMessage();
    }
}

// Query per ottenere i messaggi
try {
    $stmt = $conn->prepare("SELECT m.*, u1.nome as mittente_nome, u1.cognome as mittente_cognome,
                                   u2.nome as destinatario_nome, u2.cognome as destinatario_cognome
                         FROM messaggi m 
                         LEFT JOIN utenti u1 ON m.mittente_id = u1.id
                         LEFT JOIN utenti u2 ON m.destinatario_id = u2.id
                         WHERE m.mittente_id = :user_id OR m.destinatario_id = :user_id
                         ORDER BY m.data_invio DESC 
                         LIMIT 100");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $messaggi = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error_message = "Errore caricamento messaggi: " . $e->getMessage();
    $messaggi = [];
}

// Query per ottenere gli utenti per il form (escludi l'utente corrente)
try {
    $stmt = $conn->prepare("SELECT id, nome, cognome FROM utenti WHERE id != :user_id ORDER BY cognome, nome");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
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
    
    <!-- Collapsible for new message -->
    <div class="collapse bg-base-100 shadow-xl mb-6" id="nuovo-messaggio">
        <input type="checkbox" /> 
        <div class="collapse-title flex justify-between items-center">
            <h2 class="text-xl font-semibold">Nuovo Messaggio</h2>
            <div>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
        <div class="collapse-content"> 
            <form method="post" action="" class="space-y-4">
                <input type="hidden" name="action" value="send_message">
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Destinatario <span class="text-error">*</span></span>
                    </label>
                    <select name="destinatario_id" class="select select-bordered w-full" required>
                        <option value="">-- Seleziona Destinatario --</option>
                        <?php foreach ($utenti as $utente): ?>
                            <option value="<?php echo $utente['id']; ?>">
                                <?php echo $utente['cognome'] . ' ' . $utente['nome']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Messaggio <span class="text-error">*</span></span>
                    </label>
                    <textarea name="messaggio" class="textarea textarea-bordered h-24" required></textarea>
                </div>
                
                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">Invia Messaggio</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Card for messages list -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title">Lista Messaggi</h2>
                <div class="join">
                    <button class="btn btn-sm btn-outline join-item">
                        <i class="fas fa-filter mr-2"></i> Filtra
                    </button>
                    <button class="btn btn-sm btn-outline join-item">
                        <i class="fas fa-download mr-2"></i> Esporta
                    </button>
                    <button class="btn btn-sm btn-primary join-item" onclick="document.getElementById('nuovo-messaggio').querySelector('input').checked = true">
                        <i class="fas fa-plus mr-2"></i> Nuovo
                    </button>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Mittente</th>
                            <th>Destinatario</th>
                            <th>Messaggio</th>
                            <th>Data Invio</th>
                            <th>Stato</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($messaggi)): ?>
                            <tr>
                                <td colspan="7" class="text-center">Nessun messaggio trovato</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($messaggi as $messaggio): ?>
                                <tr>
                                    <td><?php echo $messaggio['id']; ?></td>
                                    <td><?php echo $messaggio['mittente_nome'] . ' ' . $messaggio['mittente_cognome']; ?></td>
                                    <td><?php echo $messaggio['destinatario_nome'] . ' ' . $messaggio['destinatario_cognome']; ?></td>
                                    <td><?php echo $messaggio['messaggio']; ?></td>
                                    <td><?php echo formatDate($messaggio['data_invio']); ?></td>
                                    <td>
                                        <?php if ($messaggio['letto']): ?>
                                            <span class="badge badge-success">Letto</span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">Non Letto</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="join">
                                            <button class="btn btn-sm btn-ghost join-item" title="Visualizza">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-ghost join-item" title="Elimina" onclick="return confirmDelete(<?php echo $messaggio['id']; ?>, 'messaggio')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
