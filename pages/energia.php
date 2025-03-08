<?php
require_once '../includes/functions.php';

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
            
            // Invia email di notifica
            $cliente_email = getClientEmailById($cliente_id);
            $subject = "Nuovo Contratto Energia Registrato";
            $message = "Caro cliente, il tuo contratto energia con fornitore {$fornitore} è stato registrato con successo.";
            sendNotificationEmail($cliente_email, $subject, $message);
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
    
    <!-- Card per nuovo contratto -->
    <div class="mb-6 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 bg-gray-50 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Nuovo Contratto Energia</h3>
            <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded text-sm flex items-center" 
                    onclick="toggleForm('formNuovoContratto')">
                <i class="fas fa-plus mr-2"></i> Aggiungi
            </button>
        </div>
        
        <div id="formNuovoContratto" class="hidden p-6">
            <form method="post" action="" class="space-y-4">
                <input type="hidden" name="action" value="add">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="cliente_id" class="block text-sm font-medium text-gray-700 mb-1">Cliente <span class="text-red-500">*</span></label>
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
                        <label for="fornitore" class="block text-sm font-medium text-gray-700 mb-1">Fornitore <span class="text-red-500">*</span></label>
                        <select id="fornitore" name="fornitore" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                            <option value="">-- Seleziona --</option>
                            <option value="A2A Energia">A2A Energia</option>
                            <option value="Enel Energia">Enel Energia</option>
                            <option value="WindTre Luce e Gas">WindTre Luce e Gas</option>
                            <option value="ENI Plenitude">ENI Plenitude</option>
                            <option value="Sorgenia">Sorgenia</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="tipologia" class="block text-sm font-medium text-gray-700 mb-1">Tipologia <span class="text-red-500">*</span></label>
                        <select id="tipologia" name="tipologia" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                            <option value="">-- Seleziona --</option>
                            <option value="Luce">Luce</option>
                            <option value="Gas">Gas</option>
                            <option value="Luce e Gas">Luce e Gas</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    <div class="md:col-span-3">
                        <label for="indirizzo_fornitura" class="block text-sm font-medium text-gray-700 mb-1">Indirizzo Fornitura <span class="text-red-500">*</span></label>
                        <input type="text" id="indirizzo_fornitura" name="indirizzo_fornitura" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="citta_fornitura" class="block text-sm font-medium text-gray-700 mb-1">Città Fornitura <span class="text-red-500">*</span></label>
                        <input type="text" id="citta_fornitura" name="citta_fornitura" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    </div>
                    
                    <div>
                        <label for="cap_fornitura" class="block text-sm font-medium text-gray-700 mb-1">CAP <span class="text-red-500">*</span></label>
                        <input type="text" id="cap_fornitura" name="cap_fornitura" maxlength="5" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="pod_pdr" class="block text-sm font-medium text-gray-700 mb-1">POD/PDR <span class="text-red-500">*</span></label>
                        <input type="text" id="pod_pdr" name="pod_pdr" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <p class="mt-1 text-sm text-gray-500">Codice identificativo del contatore (POD per luce, PDR per gas)</p>
                    </div>
                    
                    <div>
                        <label for="offerta" class="block text-sm font-medium text-gray-700 mb-1">Offerta <span class="text-red-500">*</span></label>
                        <input type="text" id="offerta" name="offerta" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    </div>
                </div>
                
                <div>
                    <label for="note" class="block text-sm font-medium text-gray-700 mb-1">Note</label>
                    <textarea id="note" name="note" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"></textarea>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                        Registra Contratto
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Card per lista contratti -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 bg-gray-50 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Contratti Luce e Gas</h3>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fornitore</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipologia</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">POD/PDR</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Offerta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stato</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Attivazione</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($contratti)): ?>
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">Nessun contratto trovato</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($contratti as $contratto): ?>
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo $contratto['id']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo $contratto['cognome'] . ' ' . $contratto['nome']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo $contratto['fornitore']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo $contratto['tipologia']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo $contratto['pod_pdr']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo $contratto['offerta']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <?php 
                                    $badge_class = '';
                                    switch($contratto['stato']) {
                                        case 'Attivo': $badge_class = 'bg-green-100 text-green-800'; break;
                                        case 'In lavorazione': $badge_class = 'bg-yellow-100 text-yellow-800'; break;
                                        case 'Annullato': $badge_class = 'bg-red-100 text-red-800'; break;
                                        case 'Scaduto': $badge_class = 'bg-gray-100 text-gray-800'; break;
                                        default: $badge_class = 'bg-gray-100 text-gray-800';
                                    }
                                    ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $badge_class; ?>"><?php echo $contratto['stato']; ?></span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo formatDate($contratto['data_attivazione']); ?></td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button class="text-blue-600 hover:text-blue-900" title="Visualizza">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="text-gray-600 hover:text-gray-900" title="Modifica">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-900" title="Elimina" onclick="return confirmDelete(<?php echo $contratto['id']; ?>, 'contratto')">
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
