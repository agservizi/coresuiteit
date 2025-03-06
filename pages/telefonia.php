<?php
require_once BASE_PATH . 'includes/functions.php';

// Controllo permessi
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Gestione form di inserimento nuova pratica telefonia
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $cliente_id = sanitizeInput($_POST['cliente_id']);
    $operatore = sanitizeInput($_POST['operatore']);
    $tipologia = sanitizeInput($_POST['tipologia']);
    $numero_mobile = sanitizeInput($_POST['numero_mobile']);
    $piano_tariffario = sanitizeInput($_POST['piano_tariffario']);
    $costo_mensile = sanitizeInput($_POST['costo_mensile']);
    $note = sanitizeInput($_POST['note']);
    
    try {
        $stmt = $conn->prepare("INSERT INTO telefonia (cliente_id, operatore, tipologia, numero_mobile, piano_tariffario, costo_mensile, note, data_attivazione, stato) 
                              VALUES (:cliente_id, :operatore, :tipologia, :numero_mobile, :piano_tariffario, :costo_mensile, :note, NOW(), 'In attivazione')");
        $stmt->bindParam(':cliente_id', $cliente_id);
        $stmt->bindParam(':operatore', $operatore);
        $stmt->bindParam(':tipologia', $tipologia);
        $stmt->bindParam(':numero_mobile', $numero_mobile);
        $stmt->bindParam(':piano_tariffario', $piano_tariffario);
        $stmt->bindParam(':costo_mensile', $costo_mensile);
        $stmt->bindParam(':note', $note);
        
        if ($stmt->execute()) {
            $success_message = "Pratica telefonia registrata con successo!";
        } else {
            $error_message = "Errore durante la registrazione della pratica.";
        }
    } catch(PDOException $e) {
        $error_message = "Errore database: " . $e->getMessage();
    }
}

// Query per ottenere le pratiche di telefonia
try {
    $stmt = $conn->query("SELECT t.*, c.nome, c.cognome FROM telefonia t 
                         LEFT JOIN clienti c ON t.cliente_id = c.id 
                         ORDER BY t.data_attivazione DESC 
                         LIMIT 100");
    $pratiche = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error_message = "Errore caricamento pratiche: " . $e->getMessage();
    $pratiche = [];
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
                    <h5 class="mb-0">Nuova Pratica Telefonia</h5>
                    <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#formNuovaPratica">
                        <i class="fas fa-plus me-1"></i> Aggiungi
                    </button>
                </div>
                <div class="card-body collapse" id="formNuovaPratica">
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
                                <label for="operatore" class="form-label form-required">Operatore</label>
                                <select class="form-select" id="operatore" name="operatore" required>
                                    <option value="">-- Seleziona --</option>
                                    <option value="Fastweb">Fastweb</option>
                                    <option value="Iliad">Iliad</option>
                                    <option value="WindTre">WindTre</option>
                                    <option value="TIM">TIM</option>
                                    <option value="Vodafone">Vodafone</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="tipologia" class="form-label form-required">Tipologia</label>
                                <select class="form-select" id="tipologia" name="tipologia" required>
                                    <option value="">-- Seleziona --</option>
                                    <option value="Mobile">Mobile</option>
                                    <option value="Fisso">Fisso</option>
                                    <option value="Fisso+Mobile">Fisso+Mobile</option>
                                    <option value="Internet">Internet</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="numero_mobile" class="form-label">Numero Mobile</label>
                                <input type="text" class="form-control" id="numero_mobile" name="numero_mobile">
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="piano_tariffario" class="form-label form-required">Piano Tariffario</label>
                                <input type="text" class="form-control" id="piano_tariffario" name="piano_tariffario" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="costo_mensile" class="form-label form-required">Costo Mensile (€)</label>
                                <input type="number" class="form-control" id="costo_mensile" name="costo_mensile" step="0.01" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="note" class="form-label">Note</label>
                            <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Registra Pratica</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pratiche Telefonia</h5>
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
                                    <th>Operatore</th>
                                    <th>Tipologia</th>
                                    <th>Piano</th>
                                    <th>Costo</th>
                                    <th>Stato</th>
                                    <th>Data Attivazione</th>
                                    <th>Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($pratiche)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center">Nessuna pratica trovata</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($pratiche as $pratica): ?>
                                        <tr>
                                            <td><?php echo $pratica['id']; ?></td>
                                            <td><?php echo $pratica['cognome'] . ' ' . $pratica['nome']; ?></td>
                                            <td><?php echo $pratica['operatore']; ?></td>
                                            <td><?php echo $pratica['tipologia']; ?></td>
                                            <td><?php echo $pratica['piano_tariffario']; ?></td>
                                            <td>€<?php echo number_format($pratica['costo_mensile'], 2, ',', '.'); ?></td>
                                            <td>
                                                <?php 
                                                $badge_class = '';
                                                switch($pratica['stato']) {
                                                    case 'Attivo': $badge_class = 'bg-success'; break;
                                                    case 'In attivazione': $badge_class = 'bg-warning'; break;
                                                    case 'Disattivato': $badge_class = 'bg-danger'; break;
                                                    default: $badge_class = 'bg-secondary';
                                                }
                                                ?>
                                                <span class="badge <?php echo $badge_class; ?>"><?php echo $pratica['stato']; ?></span>
                                            </td>
                                            <td><?php echo formatDate($pratica['data_attivazione']); ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" title="Visualizza">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary" title="Modifica">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" title="Elimina" onclick="return confirmDelete(<?php echo $pratica['id']; ?>, 'pratica')">
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
