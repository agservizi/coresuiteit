<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

// Controllo permessi
if (!isLoggedIn() || !hasRole('admin')) {
    header("Location: login.php");
    exit;
}

$success_message = '';
$error_message = '';

// Gestione form di creazione nuova automazione
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_automazione') {
    $nome = sanitizeInput($_POST['nome']);
    $descrizione = sanitizeInput($_POST['descrizione']);
    $trigger = sanitizeInput($_POST['trigger']);
    $azione = sanitizeInput($_POST['azione']);
    $condizioni = sanitizeInput($_POST['condizioni']);
    
    try {
        $stmt = $conn->prepare("INSERT INTO automazioni (nome, descrizione, trigger_evento, azione, condizioni, data_creazione, utente_id) 
                              VALUES (:nome, :descrizione, :trigger, :azione, :condizioni, NOW(), :utente_id)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':descrizione', $descrizione);
        $stmt->bindParam(':trigger', $trigger);
        $stmt->bindParam(':azione', $azione);
        $stmt->bindParam(':condizioni', $condizioni);
        $stmt->bindParam(':utente_id', $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            $success_message = "Automazione creata con successo!";
        } else {
            $error_message = "Errore durante la creazione dell'automazione.";
        }
    } catch(PDOException $e) {
        $error_message = "Errore database: " . $e->getMessage();
    }
}

// Query per ottenere le automazioni
try {
    $stmt = $conn->query("SELECT a.*, u.nome as utente_nome, u.cognome as utente_cognome
                         FROM automazioni a 
                         LEFT JOIN utenti u ON a.utente_id = u.id
                         ORDER BY a.data_creazione DESC 
                         LIMIT 100");
    $automazioni = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error_message = "Errore caricamento automazioni: " . $e->getMessage();
    $automazioni = [];
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
    
    <!-- Collapsible for new automazione -->
    <div class="collapse bg-base-100 shadow-xl mb-6" id="nuova-automazione">
        <input type="checkbox" /> 
        <div class="collapse-title flex justify-between items-center">
            <h2 class="text-xl font-semibold">Nuova Automazione</h2>
            <div>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
        <div class="collapse-content"> 
            <form method="post" action="" class="space-y-4">
                <input type="hidden" name="action" value="create_automazione">
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Nome <span class="text-error">*</span></span>
                    </label>
                    <input type="text" name="nome" class="input input-bordered w-full" required>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Descrizione</span>
                    </label>
                    <textarea name="descrizione" class="textarea textarea-bordered h-24"></textarea>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Trigger Evento <span class="text-error">*</span></span>
                    </label>
                    <select name="trigger" class="select select-bordered w-full" required>
                        <option value="">-- Seleziona --</option>
                        <option value="Nuovo Cliente">Nuovo Cliente</option>
                        <option value="Nuovo Ordine">Nuovo Ordine</option>
                        <option value="Pagamento Ricevuto">Pagamento Ricevuto</option>
                        <!-- Aggiungi altri trigger -->
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Azione <span class="text-error">*</span></span>
                    </label>
                    <select name="azione" class="select select-bordered w-full" required>
                        <option value="">-- Seleziona --</option>
                        <option value="Invia Email">Invia Email</option>
                        <option value="Invia SMS">Invia SMS</option>
                        <option value="Aggiorna Stato">Aggiorna Stato</option>
                        <!-- Aggiungi altre azioni -->
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Condizioni</span>
                    </label>
                    <textarea name="condizioni" class="textarea textarea-bordered h-24"></textarea>
                </div>
                
                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">Crea Automazione</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Card for automazioni list -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title">Lista Automazioni</h2>
                <div class="join">
                    <button class="btn btn-sm btn-outline join-item">
                        <i class="fas fa-filter mr-2"></i> Filtra
                    </button>
                    <button class="btn btn-sm btn-outline join-item">
                        <i class="fas fa-download mr-2"></i> Esporta
                    </button>
                    <button class="btn btn-sm btn-primary join-item" onclick="document.getElementById('nuova-automazione').querySelector('input').checked = true">
                        <i class="fas fa-plus mr-2"></i> Nuovo
                    </button>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Trigger</th>
                            <th>Azione</th>
                            <th>Data Creazione</th>
                            <th>Autore</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($automazioni)): ?>
                            <tr>
                                <td colspan="7" class="text-center">Nessuna automazione trovata</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($automazioni as $automazione): ?>
                                <tr>
                                    <td><?php echo $automazione['id']; ?></td>
                                    <td><?php echo $automazione['nome']; ?></td>
                                    <td><?php echo $automazione['trigger_evento']; ?></td>
                                    <td><?php echo $automazione['azione']; ?></td>
                                    <td><?php echo formatDate($automazione['data_creazione']); ?></td>
                                    <td><?php echo $automazione['utente_nome'] . ' ' . $automazione['utente_cognome']; ?></td>
                                    <td>
                                        <div class="join">
                                            <button class="btn btn-sm btn-ghost join-item" title="Visualizza">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-ghost join-item" title="Modifica">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-ghost join-item text-error" title="Elimina" onclick="return confirmDelete(<?php echo $automazione['id']; ?>, 'automazione')">
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
