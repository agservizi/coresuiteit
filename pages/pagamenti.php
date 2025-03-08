<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

// Controllo permessi
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Gestione form di inserimento nuovo pagamento
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $cliente_id = sanitizeInput($_POST['cliente_id']);
    $tipo_pagamento = sanitizeInput($_POST['tipo_pagamento']);
    $importo = sanitizeInput($_POST['importo']);
    $descrizione = sanitizeInput($_POST['descrizione']);
    $stato = sanitizeInput($_POST['stato']);
    
    try {
        $transaction_id = generateTransactionId();
        $stmt = $conn->prepare("INSERT INTO pagamenti (transaction_id, cliente_id, tipo, importo, descrizione, stato, data_creazione) 
                              VALUES (:transaction_id, :cliente_id, :tipo, :importo, :descrizione, :stato, NOW())");
        $stmt->bindParam(':transaction_id', $transaction_id);
        $stmt->bindParam(':cliente_id', $cliente_id);
        $stmt->bindParam(':tipo', $tipo_pagamento);
        $stmt->bindParam(':importo', $importo);
        $stmt->bindParam(':descrizione', $descrizione);
        $stmt->bindParam(':stato', $stato);
        
        if ($stmt->execute()) {
            $success_message = "Pagamento registrato con successo!";
            
            // Invia email di notifica
            $cliente_email = getClientEmailById($cliente_id);
            $subject = "Nuovo Pagamento Registrato";
            $message = "Caro cliente, il tuo pagamento di €{$importo} per {$tipo_pagamento} è stato registrato con successo.";
            sendNotificationEmail($cliente_email, $subject, $message);
        } else {
            $error_message = "Errore durante la registrazione del pagamento.";
        }
    } catch(PDOException $e) {
        $error_message = "Errore database: " . $e->getMessage();
    }
}

// Query per ottenere i pagamenti
try {
    $stmt = $conn->query("SELECT p.*, c.nome, c.cognome FROM pagamenti p 
                         LEFT JOIN clienti c ON p.cliente_id = c.id 
                         ORDER BY p.data_creazione DESC 
                         LIMIT 100");
    $pagamenti = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error_message = "Errore caricamento pagamenti: " . $e->getMessage();
    $pagamenti = [];
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
    
    <!-- Collapsible for new payment -->
    <div class="collapse bg-base-100 shadow-xl mb-6" id="nuovo-pagamento">
        <input type="checkbox" /> 
        <div class="collapse-title flex justify-between items-center">
            <h2 class="text-xl font-semibold">Nuovo Pagamento</h2>
            <div>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
        <div class="collapse-content"> 
            <form method="post" action="" class="space-y-4">
                <input type="hidden" name="action" value="add">
                
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
                            <span class="label-text">Tipo Pagamento <span class="text-error">*</span></span>
                        </label>
                        <select name="tipo_pagamento" class="select select-bordered w-full" required>
                            <option value="">-- Seleziona --</option>
                            <option value="Bollettino">Bollettino Postale</option>
                            <option value="Bonifico">Bonifico Bancario</option>
                            <option value="F24">F24</option>
                            <option value="PagoPA">PagoPA</option>
                            <option value="MAV">MAV</option>
                            <option value="RAV">RAV</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Importo (€) <span class="text-error">*</span></span>
                        </label>
                        <input type="number" name="importo" step="0.01" class="input input-bordered w-full" required>
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Stato</span>
                        </label>
                        <select name="stato" class="select select-bordered w-full">
                            <option value="Completato">Completato</option>
                            <option value="In elaborazione">In elaborazione</option>
                            <option value="Annullato">Annullato</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Descrizione</span>
                    </label>
                    <textarea name="descrizione" class="textarea textarea-bordered h-24"></textarea>
                </div>
                
                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">Registra Pagamento</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Card for payment list -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title">Lista Pagamenti</h2>
                <div class="join">
                    <button class="btn btn-sm btn-outline join-item">
                        <i class="fas fa-filter mr-2"></i> Filtra
                    </button>
                    <button class="btn btn-sm btn-outline join-item">
                        <i class="fas fa-download mr-2"></i> Esporta
                    </button>
                    <button class="btn btn-sm btn-primary join-item" onclick="document.getElementById('nuovo-pagamento').querySelector('input').checked = true">
                        <i class="fas fa-plus mr-2"></i> Nuovo
                    </button>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>ID Transazione</th>
                            <th>Cliente</th>
                            <th>Tipo</th>
                            <th>Importo</th>
                            <th>Stato</th>
                            <th>Data</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pagamenti)): ?>
                            <tr>
                                <td colspan="7" class="text-center">Nessun pagamento trovato</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pagamenti as $pagamento): ?>
                                <tr>
                                    <td><?php echo $pagamento['transaction_id']; ?></td>
                                    <td><?php echo $pagamento['cognome'] . ' ' . $pagamento['nome']; ?></td>
                                    <td><?php echo $pagamento['tipo']; ?></td>
                                    <td>€<?php echo number_format($pagamento['importo'], 2, ',', '.'); ?></td>
                                    <td>
                                        <?php 
                                        $badge_class = '';
                                        switch($pagamento['stato']) {
                                            case 'Completato': $badge_class = 'badge-success'; break;
                                            case 'In elaborazione': $badge_class = 'badge-warning'; break;
                                            case 'Annullato': $badge_class = 'badge-error'; break;
                                            default: $badge_class = 'badge-ghost';
                                        }
                                        ?>
                                        <span class="badge <?php echo $badge_class; ?>"><?php echo $pagamento['stato']; ?></span>
                                    </td>
                                    <td><?php echo formatDate($pagamento['data_creazione']); ?></td>
                                    <td>
                                        <div class="join">
                                            <button class="btn btn-sm btn-ghost join-item" title="Visualizza">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-ghost join-item" title="Modifica">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-ghost join-item text-error" title="Elimina" onclick="return confirmDelete(<?php echo $pagamento['id']; ?>, 'pagamento')">
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
