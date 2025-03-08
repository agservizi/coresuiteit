<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

// Controllo permessi
if (!isLoggedIn() || !hasRole('admin')) {
    header("Location: login.php");
    exit;
}

$success_message = '';
$error_message = '';

// Gestione form di creazione nuova campagna
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_campaign') {
    $nome = sanitizeInput($_POST['nome']);
    $descrizione = sanitizeInput($_POST['descrizione']);
    $tipo = sanitizeInput($_POST['tipo']);
    $destinatari = sanitizeInput($_POST['destinatari']);
    $contenuto = $_POST['contenuto']; // Non sanitizzare HTML
    
    try {
        $stmt = $conn->prepare("INSERT INTO campagne_marketing (nome, descrizione, tipo, destinatari, contenuto, data_creazione, utente_id) 
                              VALUES (:nome, :descrizione, :tipo, :destinatari, :contenuto, NOW(), :utente_id)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':descrizione', $descrizione);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':destinatari', $destinatari);
        $stmt->bindParam(':contenuto', $contenuto);
        $stmt->bindParam(':utente_id', $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            $success_message = "Campagna marketing creata con successo!";
        } else {
            $error_message = "Errore durante la creazione della campagna marketing.";
        }
    } catch(PDOException $e) {
        $error_message = "Errore database: " . $e->getMessage();
    }
}

// Query per ottenere le campagne marketing
try {
    $stmt = $conn->query("SELECT cm.*, u.nome as utente_nome, u.cognome as utente_cognome
                         FROM campagne_marketing cm 
                         LEFT JOIN utenti u ON cm.utente_id = u.id
                         ORDER BY cm.data_creazione DESC 
                         LIMIT 100");
    $campagne = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error_message = "Errore caricamento campagne marketing: " . $e->getMessage();
    $campagne = [];
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
    
    <!-- Collapsible for new campaign -->
    <div class="collapse bg-base-100 shadow-xl mb-6" id="nuova-campagna">
        <input type="checkbox" /> 
        <div class="collapse-title flex justify-between items-center">
            <h2 class="text-xl font-semibold">Nuova Campagna Marketing</h2>
            <div>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
        <div class="collapse-content"> 
            <form method="post" action="" class="space-y-4">
                <input type="hidden" name="action" value="create_campaign">
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Nome <span class="text-error">*</span></span>
                    </label>
                    <input type="text" name="nome" class="input input-bordered w-full" required>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Descrizione</span>
                    </label>
                    <textarea name="descrizione" class="textarea textarea-bordered h-24"></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Tipo <span class="text-error">*</span></span>
                        </label>
                        <select name="tipo" class="select select-bordered w-full" required>
                            <option value="">-- Seleziona --</option>
                            <option value="Email">Email</option>
                            <option value="SMS">SMS</option>
                            <option value="Social">Social</option>
                        </select>
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Destinatari <span class="text-error">*</span></span>
                        </label>
                        <select name="destinatari" class="select select-bordered w-full" required>
                            <option value="">-- Seleziona --</option>
                            <option value="Tutti">Tutti</option>
                            <option value="Clienti">Clienti</option>
                            <option value="Leads">Leads</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Contenuto <span class="text-error">*</span></span>
                    </label>
                    <textarea name="contenuto" class="textarea textarea-bordered h-48"></textarea>
                </div>
                
                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">Crea Campagna</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Card for marketing campaigns list -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title">Lista Campagne Marketing</h2>
                <div class="join">
                    <button class="btn btn-sm btn-outline join-item">
                        <i class="fas fa-filter mr-2"></i> Filtra
                    </button>
                    <button class="btn btn-sm btn-outline join-item">
                        <i class="fas fa-download mr-2"></i> Esporta
                    </button>
                    <button class="btn btn-sm btn-primary join-item" onclick="document.getElementById('nuova-campagna').querySelector('input').checked = true">
                        <i class="fas fa-plus mr-2"></i> Nuovo
                    </button>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Destinatari</th>
                            <th>Data Creazione</th>
                            <th>Autore</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($campagne)): ?>
                            <tr>
                                <td colspan="7" class="text-center">Nessuna campagna trovata</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($campagne as $campagna): ?>
                                <tr>
                                    <td><?php echo $campagna['id']; ?></td>
                                    <td><?php echo $campagna['nome']; ?></td>
                                    <td><?php echo $campagna['tipo']; ?></td>
                                    <td><?php echo $campagna['destinatari']; ?></td>
                                    <td><?php echo formatDate($campagna['data_creazione']); ?></td>
                                    <td><?php echo $campagna['utente_nome'] . ' ' . $campagna['utente_cognome']; ?></td>
                                    <td>
                                        <div class="join">
                                            <button class="btn btn-sm btn-ghost join-item" title="Visualizza">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-ghost join-item" title="Modifica">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-ghost join-item text-error" title="Elimina" onclick="return confirmDelete(<?php echo $campagna['id']; ?>, 'campagna')">
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
