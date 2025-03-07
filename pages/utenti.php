<?php
// Controllo permessi
if (!isLoggedIn() || !hasRole('admin')) {
    header("Location: login.php");
    exit;
}

// Gestione form di inserimento nuovo utente
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $nome = sanitizeInput($_POST['nome']);
    $cognome = sanitizeInput($_POST['cognome']);
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);
    $ruolo = sanitizeInput($_POST['ruolo']);
    
    if (registerUser($nome, $cognome, $email, $password, $ruolo)) {
        $success_message = "Utente registrato con successo!";
    } else {
        $error_message = "Errore durante la registrazione dell'utente.";
    }
}

// Query per ottenere gli utenti
try {
    $stmt = $conn->query("SELECT * FROM utenti ORDER BY cognome, nome");
    $utenti = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error_message = "Errore caricamento utenti: " . $e->getMessage();
    $utenti = [];
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
    
    <!-- Collapsible for new user -->
    <div class="collapse bg-base-100 shadow-xl mb-6" id="nuovo-utente">
        <input type="checkbox" /> 
        <div class="collapse-title flex justify-between items-center">
            <h2 class="text-xl font-semibold">Nuovo Utente</h2>
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
                            <span class="label-text">Email <span class="text-error">*</span></span>
                        </label>
                        <input type="email" name="email" class="input input-bordered w-full" required>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Password <span class="text-error">*</span></span>
                        </label>
                        <input type="password" name="password" class="input input-bordered w-full" required>
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Ruolo <span class="text-error">*</span></span>
                        </label>
                        <select name="ruolo" class="select select-bordered w-full" required>
                            <option value="operatore">Operatore</option>
                            <option value="admin">Amministratore</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">Registra Utente</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Card for users list -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title">Lista Utenti</h2>
                <div class="join">
                    <button class="btn btn-sm btn-outline join-item">
                        <i class="fas fa-filter mr-2"></i> Filtra
                    </button>
                    <button class="btn btn-sm btn-outline join-item">
                        <i class="fas fa-download mr-2"></i> Esporta
                    </button>
                    <button class="btn btn-sm btn-primary join-item" onclick="document.getElementById('nuovo-utente').querySelector('input').checked = true">
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
                            <th>Ruolo</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($utenti)): ?>
                            <tr>
                                <td colspan="6" class="text-center">Nessun utente trovato</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($utenti as $utente): ?>
                                <tr>
                                    <td><?php echo $utente['id']; ?></td>
                                    <td><?php echo $utente['nome']; ?></td>
                                    <td><?php echo $utente['cognome']; ?></td>
                                    <td><?php echo $utente['email']; ?></td>
                                    <td>
                                        <?php if ($utente['ruolo'] == 'admin'): ?>
                                            <span class="badge badge-primary">Amministratore</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Operatore</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="join">
                                            <button class="btn btn-sm btn-ghost join-item" title="Visualizza">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-ghost join-item" title="Modifica">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-ghost join-item text-error" title="Elimina" onclick="return confirmDelete(<?php echo $utente['id']; ?>, 'utente')">
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
