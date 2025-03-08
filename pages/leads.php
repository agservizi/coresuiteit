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

// Gestione form di inserimento nuovo lead
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $nome = sanitizeInput($_POST['nome']);
    $cognome = sanitizeInput($_POST['cognome']);
    $email = sanitizeInput($_POST['email']);
    $telefono = sanitizeInput($_POST['telefono']);
    $fonte = sanitizeInput($_POST['fonte']);
    $note = sanitizeInput($_POST['note']);
    
    try {
        $stmt = $conn->prepare("INSERT INTO leads (nome, cognome, email, telefono, fonte, note, data_registrazione) 
                              VALUES (:nome, :cognome, :email, :telefono, :fonte, :note, NOW())");
        
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cognome', $cognome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':fonte', $fonte);
        $stmt->bindParam(':note', $note);
        
        if ($stmt->execute()) {
            $success_message = "Lead registrato con successo!";
        } else {
            $error_message = "Errore durante la registrazione del lead.";
        }
    } catch(PDOException $e) {
        $error_message = "Errore database: " . $e->getMessage();
    }
}

// Query per ottenere i leads
try {
    $stmt = $conn->query("SELECT * FROM leads ORDER BY cognome, nome");
    $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error_message = "Errore caricamento leads: " . $e->getMessage();
    $leads = [];
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
    
    <!-- Collapsible for new lead -->
    <div class="collapse bg-base-100 shadow-xl mb-6" id="nuovo-lead">
        <input type="checkbox" /> 
        <div class="collapse-title flex justify-between items-center">
            <h2 class="text-xl font-semibold">Nuovo Lead</h2>
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
                            <span class="label-text">Nome <span class="text-error">*</span></span>
                        </label>
                        <input type="text" name="nome" class="input input-bordered w-full" required>
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Cognome <span class="text-error">*</span></span>
                        </label>
                        <input type="text" name="cognome" class="input input-bordered w-full" required>
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Email</span>
                        </label>
                        <input type="email" name="email" class="input input-bordered w-full">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Telefono <span class="text-error">*</span></span>
                        </label>
                        <input type="tel" name="telefono" class="input input-bordered w-full" required>
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Fonte</span>
                        </label>
                        <select name="fonte" class="select select-bordered w-full">
                            <option value="">-- Seleziona --</option>
                            <option value="Sito Web">Sito Web</option>
                            <option value="Social Media">Social Media</option>
                            <option value="Passaparola">Passaparola</option>
                            <option value="Altro">Altro</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Note</span>
                    </label>
                    <textarea name="note" class="textarea textarea-bordered h-24"></textarea>
                </div>
                
                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">Registra Lead</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Card for leads list -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title">Lista Leads</h2>
                <div class="join">
                    <button class="btn btn-sm btn-outline join-item">
                        <i class="fas fa-filter mr-2"></i> Filtra
                    </button>
                    <button class="btn btn-sm btn-outline join-item">
                        <i class="fas fa-download mr-2"></i> Esporta
                    </button>
                    <button class="btn btn-sm btn-primary join-item" onclick="document.getElementById('nuovo-lead').querySelector('input').checked = true">
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
                            <th>Cognome</th>
                            <th>Email</th>
                            <th>Telefono</th>
                            <th>Fonte</th>
                            <th>Data Registrazione</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($leads)): ?>
                            <tr>
                                <td colspan="8" class="text-center">Nessun lead trovato</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($leads as $lead): ?>
                                <tr>
                                    <td><?php echo $lead['id']; ?></td>
                                    <td><?php echo $lead['nome']; ?></td>
                                    <td><?php echo $lead['cognome']; ?></td>
                                    <td><?php echo $lead['email']; ?></td>
                                    <td><?php echo $lead['telefono']; ?></td>
                                    <td><?php echo $lead['fonte']; ?></td>
                                    <td><?php echo formatDate($lead['data_registrazione']); ?></td>
                                    <td>
                                        <div class="join">
                                            <button class="btn btn-sm btn-ghost join-item" title="Visualizza">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-ghost join-item" title="Modifica">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-ghost join-item text-error" title="Elimina" onclick="return confirmDelete(<?php echo $lead['id']; ?>, 'lead')">
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
