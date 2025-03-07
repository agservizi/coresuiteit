<?php
// Controllo permessi
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Gestione form di inserimento nuova spedizione
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $cliente_id = sanitizeInput($_POST['cliente_id']);
    $tipo_spedizione = sanitizeInput($_POST['tipo_spedizione']);
    $peso = sanitizeInput($_POST['peso']);
    $destinatario_nome = sanitizeInput($_POST['destinatario_nome']);
    $destinazione_indirizzo = sanitizeInput($_POST['destinazione_indirizzo']);
    $destinazione_citta = sanitizeInput($_POST['destinazione_citta']);
    $destinazione_cap = sanitizeInput($_POST['destinazione_cap']);
    $destinazione_paese = sanitizeInput($_POST['destinazione_paese']);
    $importo = sanitizeInput($_POST['importo']);
    $note = sanitizeInput($_POST['note']);
    
    try {
        $tracking_number = 'TRK' . date('YmdHis') . rand(1000, 9999);
        
        $stmt = $conn->prepare("INSERT INTO spedizioni (cliente_id, tipo, peso, tracking_number, destinatario_nome, 
                              destinazione_indirizzo, destinazione_citta, destinazione_cap, destinazione_paese, 
                              importo, note, data_spedizione, stato) 
                             VALUES (:cliente_id, :tipo, :peso, :tracking_number, :destinatario_nome, 
                             :destinazione_indirizzo, :destinazione_citta, :destinazione_cap, :destinazione_paese, 
                             :importo, :note, NOW(), 'In lavorazione')");
        
        $stmt->bindParam(':cliente_id', $cliente_id);
        $stmt->bindParam(':tipo', $tipo_spedizione);
        $stmt->bindParam(':peso', $peso);
        $stmt->bindParam(':tracking_number', $tracking_number);
        $stmt->bindParam(':destinatario_nome', $destinatario_nome);
        $stmt->bindParam(':destinazione_indirizzo', $destinazione_indirizzo);
        $stmt->bindParam(':destinazione_citta', $destinazione_citta);
        $stmt->bindParam(':destinazione_cap', $destinazione_cap);
        $stmt->bindParam(':destinazione_paese', $destinazione_paese);
        $stmt->bindParam(':importo', $importo);
        $stmt->bindParam(':note', $note);
        
        if ($stmt->execute()) {
            $success_message = "Spedizione registrata con successo! Tracking Number: " . $tracking_number;
        } else {
            $error_message = "Errore durante la registrazione della spedizione.";
        }
    } catch(PDOException $e) {
        $error_message = "Errore database: " . $e->getMessage();
    }
}

// Query per ottenere le spedizioni
try {
    $stmt = $conn->query("SELECT s.*, c.nome, c.cognome FROM spedizioni s 
                         LEFT JOIN clienti c ON s.cliente_id = c.id 
                         ORDER BY s.data_spedizione DESC 
                         LIMIT 100");
    $spedizioni = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error_message = "Errore caricamento spedizioni: " . $e->getMessage();
    $spedizioni = [];
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
                    <h5 class="mb-0">Nuova Spedizione</h5>
                    <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#formNuovaSpedizione">
                        <i class="fas fa-plus me-1"></i> Aggiungi
                    </button>
                </div>
                <div class="card-body collapse" id="formNuovaSpedizione">
                    <form method="post" action="">
                        <input type="hidden" name="action" value="add">
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="cliente_id" class="form-label form-required">Cliente Mittente</label>
                                <select class="form-select" id="cliente_id" name="cliente_id" required>
                                    <option value="">-- Seleziona Cliente --</option>
                                    <?php foreach ($clienti as $cliente): ?>
                                        <option value="<?php echo $cliente['id']; ?>">
                                            <?php echo $cliente['cognome'] . ' ' . $cliente['nome']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="tipo_spedizione" class="form-label form-required">Tipo Spedizione</label>
                                <select class="form-select" id="tipo_spedizione" name="tipo_spedizione" required>
                                    <option value="">-- Seleziona --</option>
                                    <option value="Nazionale">Nazionale</option>
                                    <option value="Internazionale">Internazionale</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="peso" class="form-label form-required">Peso (kg)</label>
                                <input type="number" class="form-control" id="peso" name="peso" step="0.01" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="destinatario_nome" class="form-label form-required">Nome Destinatario</label>
                                <input type="text" class="form-control" id="destinatario_nome" name="destinatario_nome" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="destinazione_indirizzo" class="form-label form-required">Indirizzo Destinazione</label>
                                <input type="text" class="form-control" id="destinazione_indirizzo" name="destinazione_indirizzo" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="destinazione_citta" class="form-label form-required">Città Destinazione</label>
                                <input type="text" class="form-control" id="destinazione_citta" name="destinazione_citta" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="destinazione_cap" class="form-label form-required">CAP Destinazione</label>
                                <input type="text" class="form-control" id="destinazione_cap" name="destinazione_cap" maxlength="5" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="destinazione_paese" class="form-label form-required">Paese Destinazione</label>
                                <input type="text" class="form-control" id="destinazione_paese" name="destinazione_paese" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="importo" class="form-label form-required">Importo (€)</label>
                                <input type="number" class="form-control" id="importo" name="importo" step="0.01" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="note" class="form-label">Note</label>
                                <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Registra Spedizione</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lista Spedizioni</h5>
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
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Tipo</th>
                                    <th>Peso</th>
                                    <th>Tracking Number</th>
                                    <th>Destinatario</th>
                                    <th>Destinazione</th>
                                    <th>Importo</th>
                                    <th>Stato</th>
                                    <th>Data Spedizione</th>
                                    <th>Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($spedizioni)): ?>
                                    <tr>
                                        <td colspan="11" class="text-center">Nessuna spedizione trovata</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($spedizioni as $spedizione): ?>
                                        <tr>
                                            <td><?php echo $spedizione['id']; ?></td>
                                            <td><?php echo $spedizione['cognome'] . ' ' . $spedizione['nome']; ?></td>
                                            <td><?php echo $spedizione['tipo']; ?></td>
                                            <td><?php echo number_format($spedizione['peso'], 2, ',', '.'); ?> kg</td>
                                            <td><?php echo $spedizione['tracking_number']; ?></td>
                                            <td><?php echo $spedizione['destinatario_nome']; ?></td>
                                            <td><?php echo $spedizione['destinazione_indirizzo'] . ', ' . $spedizione['destinazione_citta'] . ', ' . $spedizione['destinazione_paese']; ?></td>
                                            <td>€<?php echo number_format($spedizione['importo'], 2, ',', '.'); ?></td>
                                            <td>
                                                <?php 
                                                $badge_class = '';
                                                switch($spedizione['stato']) {
                                                    case 'In lavorazione': $badge_class = 'bg-warning'; break;
                                                    case 'Spedito': $badge_class = 'bg-info'; break;
                                                    case 'Consegnato': $badge_class = 'bg-success'; break;
                                                    case 'Annullato': $badge_class = 'bg-danger'; break;
                                                    default: $badge_class = 'bg-secondary';
                                                }
                                                ?>
                                                <span class="badge <?php echo $badge_class; ?>"><?php echo $spedizione['stato']; ?></span>
                                            </td>
                                            <td><?php echo formatDate($spedizione['data_spedizione']); ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" title="Visualizza">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary" title="Modifica">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" title="Elimina" onclick="return confirmDelete(<?php echo $spedizione['id']; ?>, 'spedizione')">
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
