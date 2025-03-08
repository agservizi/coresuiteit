<?php
require_once '../includes/functions.php';

// Controllo permessi
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Gestione form di inserimento nuova fattura
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $cliente_id = sanitizeInput($_POST['cliente_id']);
    $importo = sanitizeInput($_POST['importo']);
    $descrizione = sanitizeInput($_POST['descrizione']);
    $stato = sanitizeInput($_POST['stato']);
    
    try {
        $fattura_id = 'FT' . date('YmdHis') . rand(1000, 9999);
        $stmt = $conn->prepare("INSERT INTO fatture (fattura_id, cliente_id, importo, descrizione, stato, data_creazione) 
                              VALUES (:fattura_id, :cliente_id, :importo, :descrizione, :stato, NOW())");
        $stmt->bindParam(':fattura_id', $fattura_id);
        $stmt->bindParam(':cliente_id', $cliente_id);
        $stmt->bindParam(':importo', $importo);
        $stmt->bindParam(':descrizione', $descrizione);
        $stmt->bindParam(':stato', $stato);
        
        if ($stmt->execute()) {
            $success_message = "Fattura registrata con successo!";
        } else {
            $error_message = "Errore durante la registrazione della fattura.";
        }
    } catch(PDOException $e) {
        $error_message = "Errore database: " . $e->getMessage();
    }
}

// Query per ottenere le fatture
try {
    $stmt = $conn->query("SELECT f.*, c.nome, c.cognome FROM fatture f 
                         LEFT JOIN clienti c ON f.cliente_id = c.id 
                         ORDER BY f.data_creazione DESC 
                         LIMIT 100");
    $fatture = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error_message = "Errore caricamento fatture: " . $e->getMessage();
    $fatture = [];
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
    
    <!-- Collapsible for new invoice -->
    <div class="collapse bg-base-100 shadow-xl mb-6" id="nuova-fattura">
        <input type="checkbox" /> 
        <div class="collapse-title flex justify-between items-center">
            <h2 class="text-xl font-semibold">Nuova Fattura</h2>
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
                            <span class="label-text">Importo (€) <span class="text-error">*</span></span>
                        </label>
                        <input type="number" name="importo" step="0.01" class="input input-bordered w-full" required>
                    </div>
                </div>
                
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
                        <option value="Pagata">Pagata</option>
                        <option value="Non Pagata">Non Pagata</option>
                    </select>
                </div>
                
                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">Registra Fattura</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Card for invoice list -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title">Lista Fatture</h2>
                <div class="join">
                    <button class="btn btn-sm btn-outline join-item">
                        <i class="fas fa-filter mr-2"></i> Filtra
                    </button>
                    <button class="btn btn-sm btn-outline join-item">
                        <i class="fas fa-download mr-2"></i> Esporta
                    </button>
                    <button class="btn btn-sm btn-primary join-item" onclick="document.getElementById('nuova-fattura').querySelector('input').checked = true">
                        <i class="fas fa-plus mr-2"></i> Nuova
                    </button>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>ID Fattura</th>
                            <th>Cliente</th>
                            <th>Importo</th>
                            <th>Descrizione</th>
                            <th>Stato</th>
                            <th>Data</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($fatture)): ?>
                            <tr>
                                <td colspan="7" class="text-center">Nessuna fattura trovata</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($fatture as $fattura): ?>
                                <tr>
                                    <td><?php echo $fattura['fattura_id']; ?></td>
                                    <td><?php echo $fattura['cognome'] . ' ' . $fattura['nome']; ?></td>
                                    <td>€<?php echo number_format($fattura['importo'], 2, ',', '.'); ?></td>
                                    <td><?php echo $fattura['descrizione']; ?></td>
                                    <td>
                                        <?php 
                                        $badge_class = $fattura['stato'] == 'Pagata' ? 'badge-success' : 'badge-warning';
                                        ?>
                                        <span class="badge <?php echo $badge_class; ?>"><?php echo $fattura['stato']; ?></span>
                                    </td>
                                    <td><?php echo formatDate($fattura['data_creazione']); ?></td>
                                    <td>
                                        <div class="join">
                                            <button class="btn btn-sm btn-ghost join-item" title="Visualizza">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-ghost join-item" title="Modifica">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-ghost join-item text-error" title="Elimina" onclick="return confirmDelete(<?php echo $fattura['id']; ?>, 'fattura')">
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
