<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

// Controllo permessi
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Gestione form di inserimento nuovo articolo
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $titolo = sanitizeInput($_POST['titolo']);
    $contenuto = $_POST['contenuto']; // Non sanitizzare HTML
    $categoria = sanitizeInput($_POST['categoria']);
    
    try {
        $stmt = $conn->prepare("INSERT INTO knowledge_base (titolo, contenuto, categoria, data_creazione, utente_id) 
                              VALUES (:titolo, :contenuto, :categoria, NOW(), :utente_id)");
        $stmt->bindParam(':titolo', $titolo);
        $stmt->bindParam(':contenuto', $contenuto);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':utente_id', $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            $success_message = "Articolo della Knowledge Base creato con successo!";
        } else {
            $error_message = "Errore durante la creazione dell'articolo.";
        }
    } catch(PDOException $e) {
        $error_message = "Errore database: " . $e->getMessage();
    }
}

// Query per ottenere gli articoli della knowledge base
try {
    $stmt = $conn->query("SELECT kb.*, u.nome as utente_nome, u.cognome as utente_cognome
                         FROM knowledge_base kb 
                         LEFT JOIN utenti u ON kb.utente_id = u.id
                         ORDER BY kb.data_creazione DESC 
                         LIMIT 100");
    $articoli = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error_message = "Errore caricamento articoli: " . $e->getMessage();
    $articoli = [];
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
    
    <!-- Collapsible for new article -->
    <div class="collapse bg-base-100 shadow-xl mb-6" id="nuovo-articolo">
        <input type="checkbox" /> 
        <div class="collapse-title flex justify-between items-center">
            <h2 class="text-xl font-semibold">Nuovo Articolo Knowledge Base</h2>
            <div>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
        <div class="collapse-content"> 
            <form method="post" action="" class="space-y-4">
                <input type="hidden" name="action" value="add">
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Titolo <span class="text-error">*</span></span>
                    </label>
                    <input type="text" name="titolo" class="input input-bordered w-full" required>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Categoria <span class="text-error">*</span></span>
                    </label>
                    <select name="categoria" class="select select-bordered w-full" required>
                        <option value="">-- Seleziona --</option>
                        <option value="Generale">Generale</option>
                        <option value="Telefonia">Telefonia</option>
                        <option value="Energia">Energia</option>
                        <option value="Pagamenti">Pagamenti</option>
                        <option value="Spedizioni">Spedizioni</option>
                        <option value="Servizi Digitali">Servizi Digitali</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Contenuto <span class="text-error">*</span></span>
                    </label>
                    <textarea name="contenuto" class="textarea textarea-bordered h-48" required></textarea>
                </div>
                
                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">Crea Articolo</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Card for knowledge base articles list -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title">Lista Articoli Knowledge Base</h2>
                <div class="join">
                    <button class="btn btn-sm btn-outline join-item">
                        <i class="fas fa-filter mr-2"></i> Filtra
                    </button>
                    <button class="btn btn-sm btn-outline join-item">
                        <i class="fas fa-download mr-2"></i> Esporta
                    </button>
                    <button class="btn btn-sm btn-primary join-item" onclick="document.getElementById('nuovo-articolo').querySelector('input').checked = true">
                        <i class="fas fa-plus mr-2"></i> Nuovo
                    </button>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titolo</th>
                            <th>Categoria</th>
                            <th>Data Creazione</th>
                            <th>Autore</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($articoli)): ?>
                            <tr>
                                <td colspan="6" class="text-center">Nessun articolo trovato</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($articoli as $articolo): ?>
                                <tr>
                                    <td><?php echo $articolo['id']; ?></td>
                                    <td><?php echo $articolo['titolo']; ?></td>
                                    <td><?php echo $articolo['categoria']; ?></td>
                                    <td><?php echo formatDate($articolo['data_creazione']); ?></td>
                                    <td><?php echo $articolo['utente_nome'] . ' ' . $articolo['utente_cognome']; ?></td>
                                    <td>
                                        <div class="join">
                                            <button class="btn btn-sm btn-ghost join-item" title="Visualizza">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-ghost join-item" title="Modifica">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-ghost join-item text-error" title="Elimina" onclick="return confirmDelete(<?php echo $articolo['id']; ?>, 'articolo')">
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
