<?php
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
    
    <!-- Collapsible for new phone service -->
    <div class="collapse bg-base-100 shadow-xl mb-6" id="nuova-telefonia">
        <input type="checkbox" /> 
        <div class="collapse-title flex justify-between items-center">
            <h2 class="text-xl font-semibold">Nuova Pratica Telefonia</h2>
            <div>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
        <div class="collapse-content"> 
            <form method="post" action="" class="space-y-4">
                <input type="hidden" name="action" value="add">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                            <span class="label-text">Operatore <span class="text-error">*</span></span>
                        </label>
                        <select name="operatore" class="select select-bordered w-full" required>
                            <option value="">-- Seleziona --</option>
                            <option value="Fastweb">Fastweb</option>
                            <option value="Iliad">Iliad</option>
                            <option value="WindTre">WindTre</option>
                            <option value="TIM">TIM</option>
                            <option value="Vodafone">Vodafone</option>
                        </select>
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Tipologia <span class="text-error">*</span></span>
                        </label>
                        <select name="tipologia" class="select select-bordered w-full" required>
                            <option value="">-- Seleziona --</option>
                            <option value="Mobile">Mobile</option>
                            <option value="Fisso">Fisso</option>
                            <option value="Fisso+Mobile">Fisso+Mobile</option>
                            <option value="Internet">Internet</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Numero Mobile</span>
                        </label>
                        <input type="text" name="numero_mobile" class="input input-bordered w-full">
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Piano Tariffario <span class="text-error">*</span></span>
                        </label>
                        <input type="text" name="piano_tariffario" class="input input-bordered w-full" required>
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Costo Mensile (€) <span class="text-error">*</span></span>
                        </label>
                        <input type="number" name="costo_mensile" step="0.01" class="input input-bordered w-full" required>
                    </div>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Note</span>
                    </label>
                    <textarea name="note" class="textarea textarea-bordered h-24"></textarea>
                </div>
                
                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">Registra Pratica</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Card for phone service list -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title">Pratiche Telefonia</h2>
                <div class="join">
                    <button class="btn btn-sm btn-outline join-item">
                        <i class="fas fa-filter mr-2"></i> Filtra
                    </button>
                    <button class="btn btn-sm btn-outline join-item">
                        <i class="fas fa-download mr-2"></i> Esporta
                    </button>
                    <button class="btn btn-sm btn-primary join-item" onclick="document.getElementById('nuova-telefonia').querySelector('input').checked = true">
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
                                            case 'Attivo': $badge_class = 'badge-success'; break;
                                            case 'In attivazione': $badge_class = 'badge-warning'; break;
                                            case 'Disattivato': $badge_class = 'badge-error'; break;
                                            default: $badge_class = 'badge-ghost';
                                        }
                                        ?>
                                        <span class="badge <?php echo $badge_class; ?>"><?php echo $pratica['stato']; ?></span>
                                    </td>
                                    <td><?php echo formatDate($pratica['data_attivazione']); ?></td>
                                    <td>
                                        <div class="join">
                                            <button class="btn btn-sm btn-ghost join-item" title="Visualizza">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-ghost join-item" title="Modifica">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-ghost join-item text-error" title="Elimina" onclick="return confirmDelete(<?php echo $pratica['id']; ?>, 'pratica')">
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
