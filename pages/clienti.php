<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

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
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?php echo $success_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Ricerca clienti</h6>
                </div>
                <div class="card-body">
                    <form action="" method="GET" class="row g-3">
                        <input type="hidden" name="page" value="clienti">
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="search" class="form-label">Nome/Cognome/CF</label>
                                <input type="text" class="form-control" id="search" name="search" placeholder="Cerca...">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="servizio" class="form-label">Servizio</label>
                                <select class="form-select" id="servizio" name="servizio">
                                    <option value="">Tutti</option>
                                    <option value="telefonia">Telefonia</option>
                                    <option value="energia">Energia</option>
                                    <option value="pagamenti">Pagamenti</option>
                                    <option value="spedizioni">Spedizioni</option>
                                    <option value="servizi-digitali">Servizi Digitali</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="stato" class="form-label">Stato</label>
                                <select class="form-select" id="stato" name="stato">
                                    <option value="">Tutti</option>
                                    <option value="attivo">Attivo</option>
                                    <option value="inattivo">Inattivo</option>
                                    <option value="sospeso">Sospeso</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Cerca
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-undo me-2"></i>Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <div class="dashboard-stat bg-info-gradient">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-label">Clienti totali</div>
                        <div class="stat-value">1,452</div>
                        <div class="stat-change">
                            <i class="fas fa-arrow-up"></i> 5% nel mese corrente
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dashboard-stat bg-success-gradient">
                        <div class="stat-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="stat-label">Nuovi clienti</div>
                        <div class="stat-value">52</div>
                        <div class="stat-change">
                            <i class="fas fa-arrow-up"></i> 12% rispetto al mese scorso
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12">
                    <a href="#formNuovoCliente" class="btn btn-primary w-100" data-bs-toggle="collapse" role="button" aria-expanded="false">
                        <i class="fas fa-plus-circle me-2"></i>Nuovo cliente
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Form per nuovo cliente -->
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
    
    <!-- Tabella clienti -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="card-title">Elenco clienti</h6>
            <div>
                <button class="btn btn-sm btn-outline-secondary me-2">
                    <i class="fas fa-filter me-1"></i> Filtra
                </button>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-download me-1"></i> Esporta
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="far fa-file-excel me-2"></i>Excel</a></li>
                        <li><a class="dropdown-item" href="#"><i class="far fa-file-pdf me-2"></i>PDF</a></li>
                        <li><a class="dropdown-item" href="#"><i class="far fa-file-csv me-2"></i>CSV</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefono</th>
                            <th>Città</th>
                            <th>Cod. Fiscale</th>
                            <th>Data Reg.</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($clienti)): ?>
                            <tr>
                                <td colspan="8" class="text-center">Nessun cliente trovato</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($clienti as $cliente): ?>
                                <tr>
                                    <td><?php echo $cliente['id']; ?></td>
                                    <td><?php echo $cliente['nome'] . ' ' . $cliente['cognome']; ?></td>
                                    <td><?php echo $cliente['email']; ?></td>
                                    <td><?php echo $cliente['telefono']; ?></td>
                                    <td><?php echo $cliente['citta']; ?></td>
                                    <td><?php echo $cliente['codice_fiscale']; ?></td>
                                    <td><?php echo formatDate($cliente['data_registrazione']); ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="#" class="btn btn-outline-info" title="Visualizza"><i class="fas fa-eye"></i></a>
                                            <a href="#" class="btn btn-outline-primary" title="Modifica"><i class="fas fa-edit"></i></a>
                                            <a href="#" class="btn btn-outline-danger" title="Elimina" onclick="return confirmDelete(<?php echo $cliente['id']; ?>, 'cliente')"><i class="fas fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <nav aria-label="Paginazione">
                <ul class="pagination justify-content-center mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-chevron-left"></i></a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a></li>
                </ul>
            </nav>
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
