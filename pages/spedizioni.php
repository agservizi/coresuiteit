<?php
require_once '../includes/functions.php';
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
            
            // Invia email di notifica
            $cliente_email = getClientEmailById($cliente_id);
            $subject = "Nuova Spedizione Registrata";
            $message = "Caro cliente, la tua spedizione con tracking number {$tracking_number} è stata registrata con successo.";
            sendNotificationEmail($cliente_email, $subject, $message);
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

<div class="container mx-auto px-4">
    <?php if (!empty($success_message)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo $success_message; ?></span>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($error_message)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo $error_message; ?></span>
        </div>
    <?php endif; ?>
    
    <!-- Card per nuova spedizione -->
    <div class="mb-6 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 bg-gray-50 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Nuova Spedizione</h3>
            <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded text-sm flex items-center" 
                    onclick="toggleForm('formNuovaSpedizione')">
                <i class="fas fa-plus mr-2"></i> Aggiungi
            </button>
        </div>
        
        <div id="formNuovaSpedizione" class="hidden p-6">
            <form method="post" action="" class="space-y-4">
                <input type="hidden" name="action" value="add">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="cliente_id" class="block text-sm font-medium text-gray-700 mb-1">Cliente Mittente <span class="text-red-500">*</span></label>
                        <select id="cliente_id" name="cliente_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                            <option value="">-- Seleziona Cliente --</option>
                            <?php foreach ($clienti as $cliente): ?>
                                <option value="<?php echo $cliente['id']; ?>">
                                    <?php echo $cliente['cognome'] . ' ' . $cliente['nome']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="tipo_spedizione" class="block text-sm font-medium text-gray-700 mb-1">Tipo Spedizione <span class="text-red-500">*</span></label>
                        <select id="tipo_spedizione" name="tipo_spedizione" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                            <option value="">-- Seleziona --</option>
                            <option value="Nazionale">Nazionale</option>
                            <option value="Internazionale">Internazionale</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="peso" class="block text-sm font-medium text-gray-700 mb-1">Peso (kg) <span class="text-red-500">*</span></label>
                        <input type="number" id="peso" name="peso" step="0.01" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="destinatario_nome" class="block text-sm font-medium text-gray-700 mb-1">Nome Destinatario <span class="text-red-500">*</span></label>
                        <input type="text" id="destinatario_nome" name="destinatario_nome" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    </div>
                    
                    <div>
                        <label for="destinazione_indirizzo" class="block text-sm font-medium text-gray-700 mb-1">Indirizzo Destinazione <span class="text-red-500">*</span></label>
                        <input type="text" id="destinazione_indirizzo" name="destinazione_indirizzo" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="destinazione_citta" class="block text-sm font-medium text-gray-700 mb-1">Città Destinazione <span class="text-red-500">*</span></label>
                        <input type="text" id="destinazione_citta" name="destinazione_citta" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    </div>
                    
                    <div>
                        <label for="destinazione_cap" class="block text-sm font-medium text-gray-700 mb-1">CAP Destinazione <span class="text-red-500">*</span></label>
                        <input type="text" id="destinazione_cap" name="destinazione_cap" maxlength="5" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    </div>
                    
                    <div>
                        <label for="destinazione_paese" class="block text-sm font-medium text-gray-700 mb-1">Paese Destinazione <span class="text-red-500">*</span></label>
                        <input type="text" id="destinazione_paese" name="destinazione_paese" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="importo" class="block text-sm font-medium text-gray-700 mb-1">Importo (€) <span class="text-red-500">*</span></label>
                        <input type="number" id="importo" name="importo" step="0.01" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    </div>
                    
                    <div>
                        <label for="note" class="block text-sm font-medium text-gray-700 mb-1">Note</label>
                        <textarea id="note" name="note" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                        Registra Spedizione
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Card per lista spedizioni -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 bg-gray-50 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Lista Spedizioni</h3>
            <div class="flex space-x-2">
                <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded text-sm flex items-center">
                    <i class="fas fa-filter mr-2"></i> Filtra
                </button>
                <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded text-sm flex items-center">
                    <i class="fas fa-download mr-2"></i> Esporta
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peso</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tracking Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destinatario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Importo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stato</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($spedizioni)): ?>
                        <tr>
                            <td colspan="10" class="px-6 py-4 text-center text-sm text-gray-500">Nessuna spedizione trovata</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($spedizioni as $spedizione): ?>
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo $spedizione['id']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo $spedizione['cognome'] . ' ' . $spedizione['nome']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo $spedizione['tipo']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo number_format($spedizione['peso'], 2, ',', '.'); ?> kg</td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo $spedizione['tracking_number']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo $spedizione['destinatario_nome']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900">€<?php echo number_format($spedizione['importo'], 2, ',', '.'); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <?php 
                                    $badge_class = '';
                                    switch($spedizione['stato']) {
                                        case 'In lavorazione': $badge_class = 'bg-yellow-100 text-yellow-800'; break;
                                        case 'Spedito': $badge_class = 'bg-blue-100 text-blue-800'; break;
                                        case 'Consegnato': $badge_class = 'bg-green-100 text-green-800'; break;
                                        case 'Annullato': $badge_class = 'bg-red-100 text-red-800'; break;
                                        default: $badge_class = 'bg-gray-100 text-gray-800';
                                    }
                                    ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $badge_class; ?>"><?php echo $spedizione['stato']; ?></span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo formatDate($spedizione['data_spedizione']); ?></td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button class="text-blue-600 hover:text-blue-900" title="Visualizza">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="text-gray-600 hover:text-gray-900" title="Modifica">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-900" title="Elimina" onclick="return confirmDelete(<?php echo $spedizione['id']; ?>, 'spedizione')">
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

<script>
function toggleForm(id) {
    const form = document.getElementById(id);
    if (form.classList.contains('hidden')) {
        form.classList.remove('hidden');
    } else {
        form.classList.add('hidden');
    }
}
</script>
