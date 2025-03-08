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

// Gestione form di creazione nuovo ticket
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_ticket') {
    $cliente_id = sanitizeInput($_POST['cliente_id']);
    $titolo = sanitizeInput($_POST['titolo']);
    $descrizione = sanitizeInput($_POST['descrizione']);
    $priorita = sanitizeInput($_POST['priorita']);
    
    try {
        $stmt = $conn->prepare("INSERT INTO tickets_supporto (cliente_id, utente_id, titolo, descrizione, priorita, stato, data_apertura) 
                              VALUES (:cliente_id, :utente_id, :titolo, :descrizione, :priorita, 'Aperto', NOW())");
        $stmt->bindParam(':cliente_id', $cliente_id);
        $stmt->bindParam(':utente_id', $_SESSION['user_id']);
        $stmt->bindParam(':titolo', $titolo);
        $stmt->bindParam(':descrizione', $descrizione);
        $stmt->bindParam(':priorita', $priorita);
        
        if ($stmt->execute()) {
            $success_message = "Ticket di supporto creato con successo!";
        } else {
            $error_message = "Errore durante la creazione del ticket di supporto.";
        }
    } catch(PDOException $e) {
        $error_message = "Errore database: " . $e->getMessage();
    }
}

// Query per ottenere i ticket di supporto
try {
    $stmt = $conn->query("SELECT ts.*, c.nome, c.cognome, u.nome as utente_nome, u.cognome as utente_cognome
                         FROM tickets_supporto ts 
                         LEFT JOIN clienti c ON ts.cliente_id = c.id
                         LEFT JOIN utenti u ON ts.utente_id = u.id
                         ORDER BY ts.data_apertura DESC 
                         LIMIT 100");
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error_message = "Errore caricamento tickets di supporto: " . $e->getMessage();
    $tickets = [];
}

// Query per ottenere i clienti per il form
try {
    $stmt = $conn->query("SELECT id, nome, cognome FROM clienti ORDER BY cognome, nome");
    $clienti = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $clienti = [];
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
    
    <!-- Collapsible for new support ticket -->
    <div class="collapse bg-base-100 shadow-xl mb-6" id="nuovo-ticket">
        <input type="checkbox" /> 
        <div class="collapse-title flex justify-between items-center">
            <h2 class="text-xl font-semibold">Nuovo Ticket di Supporto</h2>
            <div>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
        <div class="collapse-content"> 
            <form method="post" action="" class="space-y-4">
                <input type="hidden" name="action" value="create_ticket">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Cliente <span class="text-error">*</span></span>
                        </label>
                        <select name="cliente_id" class="select select-bordered w-full" required>
                            <option value="">-- Seleziona Cliente --</option>
                            <?php foreach ($clienti as $cliente): ?>
                                <option value="<?php echo $cliente['id']; ?>">
                                    <?php echo $cliente['cognome'] . ' ' . $cliente['nome']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Priorità <span class="text-error">*</span></span>
                        </label>
                        <select name="priorita" class="select select-bordered w-full" required>
                            <option value="">-- Seleziona --</option>
                            <option value="Bassa">Bassa</option>
                            <option value="Media">Media</option>
                            <option value="Alta">Alta</option>
                            <option value="Urgente">Urgente</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Titolo <span class="text-error">*</span></span>
                    </label>
                    <input type="text" name="titolo" class="input input-bordered w-full" required>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Descrizione <span class="text-error">*</span></span>
                    </label>
                    <textarea name="descrizione" class="textarea textarea-bordered h-24" required></textarea>
                </div>
                
                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">Crea Ticket</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Card for support tickets list -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title">Lista Tickets di Supporto</h2>
                <div class="join">
                    <button class="btn btn-sm btn-outline join-item">
                        <i class="fas fa-filter mr-2"></i> Filtra
                    </button>
                    <button class="btn btn-sm btn-outline join-item">
                        <i class="fas fa-download mr-2"></i> Esporta
                    </button>
                    <button class="btn btn-sm btn-primary join-item" onclick="document.getElementById('nuovo-ticket').querySelector('input').checked = true">
                        <i class="fas fa-plus mr-2"></i> Nuovo
                    </button>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Titolo</th>
                            <th>Priorità</th>
                            <th>Stato</th>
                            <th>Data Apertura</th>
                            <th>Assegnato a</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tickets)): ?>
                            <tr>
                                <td colspan="8" class="text-center">Nessun ticket di supporto trovato</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($tickets as $ticket): ?>
                                <tr>
                                    <td><?php echo $ticket['id']; ?></td>
                                    <td><?php echo $ticket['cognome'] . ' ' . $ticket['nome']; ?></td>
                                    <td><?php echo $ticket['titolo']; ?></td>
                                    <td><?php echo $ticket['priorita']; ?></td>
                                    <td><?php echo $ticket['stato']; ?></td>
                                    <td><?php echo formatDate($ticket['data_apertura']); ?></td>
                                    <td><?php echo $ticket['utente_nome'] . ' ' . $ticket['utente_cognome']; ?></td>
                                    <td>
                                        <div class="join">
                                            <button class="btn btn-sm btn-ghost join-item" title="Visualizza">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-ghost join-item" title="Modifica">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-ghost join-item text-error" title="Elimina" onclick="return confirmDelete(<?php echo $ticket['id']; ?>, 'ticket')">
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
