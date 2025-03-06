<?php
require_once BASE_PATH . 'includes/functions.php';

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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Nuovo Pagamento</h5>
                    <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#formNuovoPagamento">
                        <i class="fas fa-plus me-1"></i> Aggiungi
                    </button>
                </div>
                <div class="card-body collapse" id="formNuovoPagamento">
                    <form method="post" action="">
                        <input type="hidden" name="action" value="add">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cliente_id" class="form-label form-required">Cliente</label>
                                <select class="form-select" id="cliente_id" name="cliente_id" required>
                                    <option value="">-- Seleziona Cliente --</option>
                                    <?php foreach ($clienti as $cliente): ?>
                                        <option value="<?php echo $cliente['id']; ?>">
                                            <?php echo $cliente['cognome'] . ' ' . $cliente['nome']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tipo_pagamento" class="form-label form-required">Tipo Pagamento</label>
                                <select class="form-select" id="tipo_pagamento" name="tipo_pagamento" required>
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
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="importo" class="form-label form-required">Importo (€)</label>
                                <input type="number" class="form-control" id="importo" name="importo" step="0.01" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="stato" class="form-label">Stato</label>
                                <select class="form-select" id="stato" name="stato">
                                    <option value="Completato">Completato</option>
                                    <option value="In elaborazione">In elaborazione</option>
                                    <option value="Annullato">Annullato</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descrizione" class="form-label">Descrizione</label>
                            <textarea class="form-control" id="descrizione" name="descrizione" rows="3"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Registra Pagamento</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lista Pagamenti</h5>
                    <div>
                        <button class="btn btn-sm btn-outline-secondary me-1">
                            <i class="fas fa-filter me-1"></i> Filtra
                        </button>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-download me-1"></i> Esporta
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
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
                                                    case 'Completato': $badge_class = 'bg-success'; break;
                                                    case 'In elaborazione': $badge_class = 'bg-warning'; break;
                                                    case 'Annullato': $badge_class = 'bg-danger'; break;
                                                    default: $badge_class = 'bg-secondary';
                                                }
                                                ?>
                                                <span class="badge <?php echo $badge_class; ?>"><?php echo $pagamento['stato']; ?></span>
                                            </td>
                                            <td><?php echo formatDate($pagamento['data_creazione']); ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" title="Visualizza">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary" title="Modifica">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" title="Elimina" onclick="return confirmDelete(<?php echo $pagamento['id']; ?>, 'pagamento')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
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
    </div>
</div>
