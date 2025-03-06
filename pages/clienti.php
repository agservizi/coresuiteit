<?php
require_once BASE_PATH . 'includes/functions.php';

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

<div class="container-fluid">
    <?php if (!empty($success_message)): ?>
        <?php echo showAlert($success_message, 'success'); ?>
    <?php endif; ?>
    
    <?php if (!empty($error_message)): ?>
        <?php echo showAlert($error_message, 'danger'); ?>
    <?php endif; ?>
    
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Nuovo Cliente</h5>
                    <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#formNuovoCliente">
                        <i class="fas fa-plus me-1"></i> Aggiungi
                    </button>
                </div>
                <div class="card-body collapse" id="formNuovoCliente">
                    <form method="post" action="">
                        <input type="hidden" name="action" value="add">
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="nome" class="form-label form-required">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="cognome" class="form-label form-required">Cognome</label>
                                <input type="text" class="form-control" id="cognome" name="cognome" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="codice_fiscale" class="form-label">Codice Fiscale</label>
                                <input type="text" class="form-control" id="codice_fiscale" name="codice_fiscale">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label form-required">Telefono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="indirizzo" class="form-label">Indirizzo</label>
                                <input type="text" class="form-control" id="indirizzo" name="indirizzo">
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="citta" class="form-label">Città</label>
                                <input type="text" class="form-control" id="citta" name="citta">
                            </div>
                            
                            <div class="col-md-2 mb-3">
                                <label for="cap" class="form-label">CAP</label>
                                <input type="text" class="form-control" id="cap" name="cap" maxlength="5">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="note" class="form-label">Note</label>
                            <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Registra Cliente</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lista Clienti</h5>
                    <div>
                        <button class="btn btn-sm btn-outline-secondary me-1">
                            <i class="fas fa-filter me-1"></i> Filtra
                        </button>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-download me-1"></i> Esporta
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Cognome</th>
                                    <th>Codice Fiscale</th>
                                    <th>Email</th>
                                    <th>Telefono</th>
                                    <th>Città</th>
                                    <th>Data Registrazione</th>
                                    <th>Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($clienti)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center">Nessun cliente trovato</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($clienti as $cliente): ?>
                                        <tr>
                                            <td><?php echo $cliente['id']; ?></td>
                                            <td><?php echo $cliente['nome']; ?></td>
                                            <td><?php echo $cliente['cognome']; ?></td>
                                            <td><?php echo $cliente['codice_fiscale']; ?></td>
                                            <td><?php echo $cliente['email']; ?></td>
                                            <td><?php echo $cliente['telefono']; ?></td>
                                            <td><?php echo $cliente['citta']; ?></td>
                                            <td><?php echo formatDate($cliente['data_registrazione']); ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" title="Visualizza">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary" title="Modifica">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" title="Elimina" onclick="return confirmDelete(<?php echo $cliente['id']; ?>, 'cliente')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
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
    </div>
</div>
