<?php
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
    
    <!-- Collapsible for new digital service -->
    <div class="collapse bg-base-100 shadow-xl mb-6" id="nuovo-servizio">
        <input type="checkbox" /> 
        <div class="collapse-title flex justify-between items-center">
            <h2 class="text-xl font-semibold">Nuovo Servizio Digitale</h2>
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
                            <span class="label-text">Tipo Servizio <span class="text-error">*</span></span>
                        </label>
                        <select name="tipo_servizio" class="select select-bordered w-full" required>
                            <option value="">-- Seleziona --</option>
                            <option value="SPID">SPID</option>
                            <option value="PEC">PEC</option>
                            <option value="Firma Digitale">Firma Digitale</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Descrizione</span>
                        </label>
                        <textarea name="descrizione" class="textarea textarea-bordered h-24"></textarea>
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
                
                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">Registra Servizio</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Card for digital services list -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title">Lista Servizi Digitali</h2>
                <div class="join">
                    <button class="btn btn-sm btn-outline join-item">
                        <i class="fas fa-filter mr-2"></i> Filtra
                    </button>
                    <button class="btn btn-sm btn-outline join-item">
                        <i class="fas fa-download mr-2"></i> Esporta
                    </button>
                    <button class="btn btn-sm btn-primary join-item" onclick="document.getElementById('nuovo-servizio').querySelector('input').checked = true">
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
                                            case 'Completato': $badge_class = 'badge-success'; break;
                                            case 'In elaborazione': $badge_class = 'badge-warning'; break;
                                            case 'Annullato': $badge_class = 'badge-error'; break;
                                            default: $badge_class = 'badge-ghost';
                                        }
                                        ?>
                                        <span class="badge <?php echo $badge_class; ?>"><?php echo $servizio['stato']; ?></span>
                                    </td>
                                    <td><?php echo formatDate($servizio['data_creazione']); ?></td>
                                    <td>
                                        <div class="join">
                                            <button class="btn btn-sm btn-ghost join-item" title="Visualizza">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-ghost join-item" title="Modifica">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-ghost join-item text-error" title="Elimina" onclick="return confirmDelete(<?php echo $servizio['id']; ?>, 'servizio')">
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
