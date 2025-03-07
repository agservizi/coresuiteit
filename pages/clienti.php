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

<div class="container-fluid">
    <?php if (!empty($success_message)): ?>
        <?php echo showAlert($success_message, 'success'); ?>
    <?php endif; ?>
    
    <?php if (!empty($error_message)): ?>
        <?php echo showAlert($error_message, 'danger'); ?>
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
                    <a href="#" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#nuovoClienteModal">
                        <i class="fas fa-plus-circle me-2"></i>Nuovo cliente
                    </a>
                </div>
            </div>
        </div>
    </div>
    
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
    
    <div class="card">
        <div class="card-header">
            <h6 class="card-title">Elenco clienti</h6>
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
                            <th>Servizi attivi</th>
                            <th>Stato</th>
                            <th>Ultima attività</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1001</td>
                            <td>Mario Rossi</td>
                            <td>mario.rossi@email.it</td>
                            <td>+39 333 123 4567</td>
                            <td><span class="badge badge-soft-primary">Telefonia</span> <span class="badge badge-soft-info">Servizi Digitali</span></td>
                            <td><span class="badge badge-soft-success">Attivo</span></td>
                            <td>15/05/2023</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="#" class="btn btn-outline-info" data-bs-toggle="tooltip" title="Visualizza"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Modifica"><i class="fas fa-edit"></i></a>
                                    <a href="#" class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Elimina" onclick="return confirmDelete(1001, 'cliente')"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>1002</td>
                            <td>Giulia Verdi</td>
                            <td>giulia.verdi@email.it</td>
                            <td>+39 335 987 6543</td>
                            <td><span class="badge badge-soft-warning">Energia</span></td>
                            <td><span class="badge badge-soft-success">Attivo</span></td>
                            <td>12/05/2023</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="#" class="btn btn-outline-info" data-bs-toggle="tooltip" title="Visualizza"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Modifica"><i class="fas fa-edit"></i></a>
                                    <a href="#" class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Elimina" onclick="return confirmDelete(1002, 'cliente')"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>1003</td>
                            <td>Luca Bianchi</td>
                            <td>luca.bianchi@email.it</td>
                            <td>+39 339 456 7890</td>
                            <td><span class="badge badge-soft-primary">Pagamenti</span> <span class="badge badge-soft-info">Telefonia</span></td>
                            <td><span class="badge badge-soft-warning">In sospeso</span></td>
                            <td>10/05/2023</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="#" class="btn btn-outline-info" data-bs-toggle="tooltip" title="Visualizza"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Modifica"><i class="fas fa-edit"></i></a>
                                    <a href="#" class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Elimina" onclick="return confirmDelete(1003, 'cliente')"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>1004</td>
                            <td>Anna Neri</td>
                            <td>anna.neri@email.it</td>
                            <td>+39 331 234 5678</td>
                            <td><span class="badge badge-soft-info">Servizi Digitali</span></td>
                            <td><span class="badge badge-soft-danger">Inattivo</span></td>
                            <td>05/05/2023</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="#" class="btn btn-outline-info" data-bs-toggle="tooltip" title="Visualizza"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Modifica"><i class="fas fa-edit"></i></a>
                                    <a href="#" class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Elimina" onclick="return confirmDelete(1004, 'cliente')"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>1005</td>
                            <td>Paolo Gialli</td>
                            <td>paolo.gialli@email.it</td>
                            <td>+39 338 567 8901</td>
                            <td><span class="badge badge-soft-warning">Spedizioni</span></td>
                            <td><span class="badge badge-soft-success">Attivo</span></td>
                            <td>01/05/2023</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="#" class="btn btn-outline-info" data-bs-toggle="tooltip" title="Visualizza"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Modifica"><i class="fas fa-edit"></i></a>
                                    <a href="#" class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Elimina" onclick="return confirmDelete(1005, 'cliente')"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
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

<!-- Modal per nuovo cliente -->
<div class="modal fade" id="nuovoClienteModal" tabindex="-1" aria-labelledby="nuovoClienteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuovoClienteModalLabel">Aggiungi nuovo cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="nuovoClienteForm" class="row g-3">
                    <div class="col-md-6">
                        <label for="nome" class="form-label form-required">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>
                    <div class="col-md-6">
                        <label for="cognome" class="form-label form-required">Cognome</label>
                        <input type="text" class="form-control" id="cognome" name="cognome" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label form-required">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="col-md-6">
                        <label for="telefono" class="form-label form-required">Telefono</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono" required>
                    </div>
                    <div class="col-md-6">
                        <label for="codiceFiscale" class="form-label form-required">Codice Fiscale</label>
                        <input type="text" class="form-control" id="codiceFiscale" name="codiceFiscale" required>
                    </div>
                    <div class="col-md-6">
                        <label for="partitaIva" class="form-label">Partita IVA</label>
                        <input type="text" class="form-control" id="partitaIva" name="partitaIva">
                    </div>
                    <div class="col-12">
                        <label for="indirizzo" class="form-label">Indirizzo</label>
                        <input type="text" class="form-control" id="indirizzo" name="indirizzo">
                    </div>
                    <div class="col-md-4">
                        <label for="citta" class="form-label">Città</label>
                        <input type="text" class="form-control" id="citta" name="citta">
                    </div>
                    <div class="col-md-4">
                        <label for="provincia" class="form-label">Provincia</label>
                        <input type="text" class="form-control" id="provincia" name="provincia" maxlength="2">
                    </div>
                    <div class="col-md-4">
                        <label for="cap" class="form-label">CAP</label>
                        <input type="text" class="form-control" id="cap" name="cap" maxlength="5">
                    </div>
                    <div class="col-md-6">
                        <label for="servizi" class="form-label">Servizi</label>
                        <select class="form-select" id="servizi" name="servizi[]" multiple>
                            <option value="telefonia">Telefonia</option>
                            <option value="energia">Energia</option>
                            <option value="pagamenti">Pagamenti</option>
                            <option value="spedizioni">Spedizioni</option>
                            <option value="servizi-digitali">Servizi Digitali</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="stato" class="form-label">Stato</label>
                        <select class="form-select" id="stato" name="stato">
                            <option value="attivo" selected>Attivo</option>
                            <option value="inattivo">Inattivo</option>
                            <option value="sospeso">In sospeso</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="note" class="form-label">Note</label>
                        <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="submit" form="nuovoClienteForm" class="btn btn-primary">Salva</button>
            </div>
        </div>
    </div>
</div>
