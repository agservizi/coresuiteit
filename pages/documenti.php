<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

// Controllo permessi
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$success_message = '';
$error_message = '';

// Gestione caricamento nuovo documento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'upload') {
    $cliente_id = sanitizeInput($_POST['cliente_id']);
    $nome = sanitizeInput($_POST['nome']);
    $tipo = sanitizeInput($_POST['tipo']);
    $note = sanitizeInput($_POST['note']);
    
    // Controlla se è stato caricato un file
    if (isset($_FILES['documento']) && $_FILES['documento']['error'] == 0) {
        $file = $_FILES['documento'];
        $filename = caricaFile($file, $tipo, $cliente_id);
        
        if ($filename) {
            if (registraDocumento($nome, $filename, $cliente_id, $tipo, $note)) {
                $success_message = "Documento caricato con successo!";
            } else {
                $error_message = "Errore durante la registrazione del documento nel database.";
            }
        } else {
            $error_message = "Errore durante il caricamento del file. Verifica tipo ed estensione.";
        }
    } else {
        $error_message = "Seleziona un file da caricare.";
    }
}

// Query per ottenere i documenti
try {
    $stmt = $conn->query("SELECT d.*, c.nome as cliente_nome, c.cognome as cliente_cognome 
                         FROM documenti d 
                         LEFT JOIN clienti c ON d.cliente_id = c.id 
                         ORDER BY d.data_caricamento DESC 
                         LIMIT 100");
    $documenti = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error_message = "Errore caricamento documenti: " . $e->getMessage();
    $documenti = [];
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
    
    <!-- Card per caricamento nuovo documento -->
    <div class="mb-6 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 bg-gray-50 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Carica Nuovo Documento</h3>
            <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded text-sm flex items-center" 
                    onclick="toggleForm('formNuovoDocumento')">
                <i class="fas fa-plus mr-2"></i> Carica
            </button>
        </div>
        
        <div id="formNuovoDocumento" class="hidden p-6">
            <form method="post" action="" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="action" value="upload">
                
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
                        <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome Documento <span class="text-red-500">*</span></label>
                        <input type="text" id="nome" name="nome" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    </div>
                    
                    <div>
                        <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo Documento <span class="text-red-500">*</span></label>
                        <select id="tipo" name="tipo" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                            <option value="">-- Seleziona --</option>
                            <option value="Contratto">Contratto</option>
                            <option value="Fattura">Fattura</option>
                            <option value="Documento Identità">Documento Identità</option>
                            <option value="Bolletta">Bolletta</option>
                            <option value="Ricevuta">Ricevuta</option>
                            <option value="Altro">Altro</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label for="documento" class="block text-sm font-medium text-gray-700 mb-1">File <span class="text-red-500">*</span></label>
                    <input type="file" id="documento" name="documento" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    <p class="mt-1 text-sm text-gray-500">Formati accettati: PDF, DOC, DOCX, JPG, PNG (max 10MB)</p>
                </div>
                
                <div>
                    <label for="note" class="block text-sm font-medium text-gray-700 mb-1">Note</label>
                    <textarea id="note" name="note" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"></textarea>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                        Carica Documento
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Card per lista documenti -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 bg-gray-50 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Documenti</h3>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($documenti)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Nessun documento trovato</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($documenti as $documento): ?>
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo $documento['id']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo $documento['cliente_cognome'] . ' ' . $documento['cliente_nome']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo $documento['nome']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo $documento['tipo']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo formatDate($documento['data_caricamento']); ?></td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="../uploads/cliente_<?php echo $documento['cliente_id']; ?>/<?php echo $documento['filename']; ?>" target="_blank" class="text-blue-600 hover:text-blue-900" title="Visualizza">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="text-green-600 hover:text-green-900" title="Scarica" onclick="downloadDocument(<?php echo $documento['cliente_id']; ?>, '<?php echo $documento['filename']; ?>')">
                                            <i class="fas fa-download"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-900" title="Elimina" onclick="return confirmDeleteDoc(<?php echo $documento['id']; ?>)">
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

function downloadDocument(clienteId, filename) {
    window.location.href = "../api/download_document.php?cliente_id=" + clienteId + "&filename=" + filename;
}

function confirmDeleteDoc(id) {
    if (confirm('Sei sicuro di voler eliminare questo documento?')) {
        window.location.href = "?action=delete&id=" + id;
        return true;
    }
    return false;
}
</script>
