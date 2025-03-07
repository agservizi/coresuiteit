<?php
// Controllo permessi
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Gestione form di inserimento nuovo cliente
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $nome = sanitizeInput($_POST['nome']);
    $cognome = sanitizeInput($_POST['cognome']);
    $codice_fiscale = sanitizeInput($_POST['codice_fiscale']);
    $email = sanitizeInput($_POST['email']);
    $telefono = sanitizeInput($_POST['telefono']);
    $indirizzo = sanitizeInput($_POST['indirizzo']);
    $citta = sanitizeInput($_POST['citta']);
    $cap = sanitizeInput($_POST['cap']);
    $note = sanitizeInput($_POST['note']);
    
    try {
        $stmt = $conn->prepare("INSERT INTO clienti (nome, cognome, codice_fiscale, email, telefono, indirizzo, citta, cap, note, data_registrazione) 
                              VALUES (:nome, :cognome, :codice_fiscale, :email, :telefono, :indirizzo, :citta, :cap, :note, NOW())");
        
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cognome', $cognome);
        $stmt->bindParam(':codice_fiscale', $codice_fiscale);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':indirizzo', $indirizzo);
        $stmt->bindParam(':citta', $citta);
        $stmt->bindParam(':cap', $cap);
        $stmt->bindParam(':note', $note);
        
        if ($stmt->execute()) {
            $success_message = "Cliente registrato con successo!";
        } else {
            $error_message = "Errore durante la registrazione del cliente.";
        }
    } catch(PDOException $e) {
        $error_message = "Errore database: " . $e->getMessage();
    }
}

// Query per ottenere i clienti
try {
    $stmt = $conn->query("SELECT * FROM clienti ORDER BY cognome, nome");
    $clienti = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error_message = "Errore caricamento clienti: " . $e->getMessage();
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
    
    <!-- Card per nuovo cliente -->
    <div class="mb-6 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 bg-gray-50 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Nuovo Cliente</h3>
            <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded text-sm flex items-center" 
                    onclick="toggleForm('formNuovoCliente')">
                <i class="fas fa-plus mr-2"></i> Aggiungi
            </button>
        </div>
        
        <div id="formNuovoCliente" class="hidden p-6">
            <form method="post" action="" class="space-y-4">
                <input type="hidden" name="action" value="add">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                        <input type="text" id="nome" name="nome" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    </div>
                    
                    <div>
                        <label for="cognome" class="block text-sm font-medium text-gray-700 mb-1">Cognome <span class="text-red-500">*</span></label>
                        <input type="text" id="cognome" name="cognome" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    </div>
                    
                    <div>
                        <label for="codice_fiscale" class="block text-sm font-medium text-gray-700 mb-1">Codice Fiscale</label>
                        <input type="text" id="codice_fiscale" name="codice_fiscale" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    </div>
                    
                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Telefono <span class="text-red-500">*</span></label>
                        <input type="tel" id="telefono" name="telefono" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <label for="indirizzo" class="block text-sm font-medium text-gray-700 mb-1">Indirizzo</label>
                        <input type="text" id="indirizzo" name="indirizzo" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    </div>
                    
                    <div>
                        <label for="citta" class="block text-sm font-medium text-gray-700 mb-1">Città</label>
                        <input type="text" id="citta" name="citta" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    </div>
                    
                    <div>
                        <label for="cap" class="block text-sm font-medium text-gray-700 mb-1">CAP</label>
                        <input type="text" id="cap" name="cap" maxlength="5" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    </div>
                </div>
                
                <div>
                    <label for="note" class="block text-sm font-medium text-gray-700 mb-1">Note</label>
                    <textarea id="note" name="note" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"></textarea>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                        Registra Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Card per lista clienti -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 bg-gray-50 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Lista Clienti</h3>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cognome</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Codice Fiscale</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telefono</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Città</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Registrazione</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($clienti)): ?>
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">Nessun cliente trovato</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clienti as $cliente): ?>
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo $cliente['id']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo $cliente['nome']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo $cliente['cognome']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo $cliente['codice_fiscale']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo $cliente['email']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo $cliente['telefono']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo $cliente['citta']; ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900"><?php echo formatDate($cliente['data_registrazione']); ?></td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button class="text-blue-600 hover:text-blue-900" title="Visualizza">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="text-gray-600 hover:text-gray-900" title="Modifica">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-900" title="Elimina" onclick="return confirmDelete(<?php echo $cliente['id']; ?>, 'cliente')">
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
