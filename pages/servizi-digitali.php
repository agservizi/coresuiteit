<?php
require_once '../includes/functions.php';

// Controllo permessi
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Gestione form di inserimento nuovo servizio digitale
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $cliente_id = sanitizeInput($_POST['cliente_id']);
    $tipo_servizio = sanitizeInput($_POST['tipo_servizio']);
    $descrizione = sanitizeInput($_POST['descrizione']);
    $stato = sanitizeInput($_POST['stato']);
    
    try {
        $stmt = $conn->prepare("INSERT INTO servizi_digitali (cliente_id, tipo_servizio, descrizione, stato, data_creazione) 
                              VALUES (:cliente_id, :tipo_servizio, :descrizione, :stato, NOW())");
        $stmt->bindParam(':cliente_id', $cliente_id);
        $stmt->bindParam(':tipo_servizio', $tipo_servizio);
        $stmt->bindParam(':descrizione', $descrizione);
        $stmt->bindParam(':stato', $stato);
        
        if ($stmt->execute()) {
            $success_message = "Servizio digitale registrato con successo!";
        } else {
            $error_message = "Errore durante la registrazione del servizio digitale.";
        }
    } catch(PDOException $e) {
        $error_message = "Errore database: " . $e->getMessage();
    }
}

// Query per ottenere i servizi digitali
try {
    $stmt = $conn->query("SELECT sd.*, c.nome, c.cognome FROM servizi_digitali sd 
                         LEFT JOIN clienti c ON sd.cliente_id = c.id 
                         ORDER BY sd.data_creazione DESC 
                         LIMIT 100");
    $servizi_digitali = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error_message = "Errore caricamento servizi digitali: " . $e->getMessage();
    $servizi_digitali = [];
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
                    <h5 class="mb-0">Nuovo Servizio Digitale</h5>
                    <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#formNuovoServizio">
                        <i class="fas fa-plus me-1"></i> Aggiungi
                    </button>
                </div>
                <div class="card-body collapse" id="formNuovoServizio">
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
                                <label for="tipo_servizio" class="form-label form-required">Tipo Servizio</label>
                                <select class="form-select" id="tipo_servizio" name="tipo_servizio" required>
                                    <option value="">-- Seleziona --</option>
                                    <option value="SPID">SPID</option>
                                    <option value="PEC">PEC</option>
                                    <option value="Firma Digitale">Firma Digitale</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descrizione" class="form-label">Descrizione</label>
                            <textarea class="form-control" id="descrizione" name="descrizione" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="stato" class="form-label">Stato</label>
                            <select class="form-select" id="stato" name="stato">
                                <option value="Completato">Completato</option>
                                <option value="In elaborazione">In elaborazione</option>
                                <option value="Annullato">Annullato</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Registra Servizio</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lista Servizi Digitali</h5>
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
                                    <th>Tipo Servizio</th>
                                    <th>Descrizione</th>
                                    <th>Stato</th>
                                    <th>Data</th>
                                    <th>Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($servizi_digitali)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Nessun servizio digitale trovato</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($servizi_digitali as $servizio): ?>
                                        <tr>
                                            <td><?php echo $servizio['id']; ?></td>
                                            <td><?php echo $servizio['cognome'] . ' ' . $servizio['nome']; ?></td>
                                            <td><?php echo $servizio['tipo_servizio']; ?></td>
                                            <td><?php echo $servizio['descrizione']; ?></td>
                                            <td>
                                                <?php 
                                                $badge_class = '';
                                                switch($servizio['stato']) {
                                                    case 'Completato': $badge_class = 'bg-success'; break;
                                                    case 'In elaborazione': $badge_class = 'bg-warning'; break;
                                                    case 'Annullato': $badge_class = 'bg-danger'; break;
                                                    default: $badge_class = 'bg-secondary';
                                                }
                                                ?>
                                                <span class="badge <?php echo $badge_class; ?>"><?php echo $servizio['stato']; ?></span>
                                            </td>
                                            <td><?php echo formatDate($servizio['data_creazione']); ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" title="Visualizza">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary" title="Modifica">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" title="Elimina" onclick="return confirmDelete(<?php echo $servizio['id']; ?>, 'servizio')">
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
