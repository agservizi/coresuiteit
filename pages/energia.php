<?php
require_once BASE_PATH . 'includes/functions.php';

// Controllo permessi
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Gestione form di inserimento nuovo contratto
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $cliente_id = sanitizeInput($_POST['cliente_id']);
    $fornitore = sanitizeInput($_POST['fornitore']);
    $tipologia = sanitizeInput($_POST['tipologia']);
    $indirizzo_fornitura = sanitizeInput($_POST['indirizzo_fornitura']);
    $citta_fornitura = sanitizeInput($_POST['citta_fornitura']);
    $cap_fornitura = sanitizeInput($_POST['cap_fornitura']);
    $pod_pdr = sanitizeInput($_POST['pod_pdr']);
    $offerta = sanitizeInput($_POST['offerta']);
    $note = sanitizeInput($_POST['note']);
    
    try {
        $stmt = $conn->prepare("INSERT INTO energia (cliente_id, fornitore, tipologia, indirizzo_fornitura, citta_fornitura, cap_fornitura, 
                               pod_pdr, offerta, note, data_attivazione, stato) 
                              VALUES (:cliente_id, :fornitore, :tipologia, :indirizzo_fornitura, :citta_fornitura, :cap_fornitura, 
                               :pod_pdr, :offerta, :note, NOW(), 'In lavorazione')");
        
        $stmt->bindParam(':cliente_id', $cliente_id);
        $stmt->bindParam(':fornitore', $fornitore);
        $stmt->bindParam(':tipologia', $tipologia);
        $stmt->bindParam(':indirizzo_fornitura', $indirizzo_fornitura);
        $stmt->bindParam(':citta_fornitura', $citta_fornitura);
        $stmt->bindParam(':cap_fornitura', $cap_fornitura);
        $stmt->bindParam(':pod_pdr', $pod_pdr);
        $stmt->bindParam(':offerta', $offerta);
        $stmt->bindParam(':note', $note);
        
        if ($stmt->execute()) {
            $success_message = "Contratto registrato con successo!";
        } else {
            $error_message = "Errore durante la registrazione del contratto.";
        }
    } catch(PDOException $e) {
        $error_message = "Errore database: " . $e->getMessage();
    }
}

// Query per ottenere i contratti energia
try {
    $stmt = $conn->query("SELECT e.*, c.nome, c.cognome FROM energia e 
                         LEFT JOIN clienti c ON e.cliente_id = c.id 
                         ORDER BY e.data_attivazione DESC 
                         LIMIT 100");
    $contratti = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error_message = "Errore caricamento contratti: " . $e->getMessage();
    $contratti = [];
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
                    <h5 class="mb-0">Nuovo Contratto Energia</h5>
                    <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#formNuovoContratto">
                        <i class="fas fa-plus me-1"></i> Aggiungi
                    </button>
                </div>
                <div class="card-body collapse" id="formNuovoContratto">
                    <form method="post" action="">
                        <input type="hidden" name="action" value="add">
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
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
                            
                            <div class="col-md-4 mb-3">
                                <label for="fornitore" class="form-label form-required">Fornitore</label>
                                <select class="form-select" id="fornitore" name="fornitore" required>
                                    <option value="">-- Seleziona --</option>
                                    <option value="A2A Energia">A2A Energia</option>
                                    <option value="Enel Energia">Enel Energia</option>
                                    <option value="WindTre Luce e Gas">WindTre Luce e Gas</option>
                                    <option value="ENI Plenitude">ENI Plenitude</option>
                                    <option value="Sorgenia">Sorgenia</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="tipologia" class="form-label form-required">Tipologia</label>
                                <select class="form-select" id="tipologia" name="tipologia" required>
                                    <option value="">-- Seleziona --</option>
                                    <option value="Luce">Luce</option>
                                    <option value="Gas">Gas</option>
                                    <option value="Luce e Gas">Luce e Gas</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="indirizzo_fornitura" class="form-label form-required">Indirizzo Fornitura</label>
                                <input type="text" class="form-control" id="indirizzo_fornitura" name="indirizzo_fornitura" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="citta_fornitura" class="form-label form-required">Città Fornitura</label>
                                <input type="text" class="form-control" id="citta_fornitura" name="citta_fornitura" required>
                            </div>
                            
                            <div class="col-md-2 mb-3">
                                <label for="cap_fornitura" class="form-label form-required">CAP</label>
                                <input type="text" class="form-control" id="cap_fornitura" name="cap_fornitura" maxlength="5" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pod_pdr" class="form-label form-required">POD/PDR</label>
                                <input type="text" class="form-control" id="pod_pdr" name="pod_pdr" required>
                                <small class="form-text text-muted">Codice identificativo del contatore (POD per luce, PDR per gas)</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="offerta" class="form-label form-required">Offerta</label>
                                <input type="text" class="form-control" id="offerta" name="offerta" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="note" class="form-label">Note</label>
                            <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Registra Contratto</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Contratti Luce e Gas</h5>
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
                                    <th>Fornitore</th>
                                    <th>Tipologia</th>
                                    <th>POD/PDR</th>
                                    <th>Offerta</th>
                                    <th>Stato</th>
                                    <th>Data Attivazione</th>
                                    <th>Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($contratti)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center">Nessun contratto trovato</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($contratti as $contratto): ?>
                                        <tr>
                                            <td><?php echo $contratto['id']; ?></td>
                                            <td><?php echo $contratto['cognome'] . ' ' . $contratto['nome']; ?></td>
                                            <td><?php echo $contratto['fornitore']; ?></td>
                                            <td><?php echo $contratto['tipologia']; ?></td>
                                            <td><?php echo $contratto['pod_pdr']; ?></td>
                                            <td><?php echo $contratto['offerta']; ?></td>
                                            <td>
                                                <?php 
                                                $badge_class = '';
                                                switch($contratto['stato']) {
                                                    case 'Attivo': $badge_class = 'bg-success'; break;
                                                    case 'In lavorazione': $badge_class = 'bg-warning'; break;
                                                    case 'Annullato': $badge_class = 'bg-danger'; break;
                                                    case 'Scaduto': $badge_class = 'bg-secondary'; break;
                                                    default: $badge_class = 'bg-secondary';
                                                }
                                                ?>
                                                <span class="badge <?php echo $badge_class; ?>"><?php echo $contratto['stato']; ?></span>
                                            </td>
                                            <td><?php echo formatDate($contratto['data_attivazione']); ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" title="Visualizza">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary" title="Modifica">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" title="Elimina" onclick="return confirmDelete(<?php echo $contratto['id']; ?>, 'contratto')">
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
