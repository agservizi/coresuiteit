<?php
// Controllo permessi
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$success_message = '';
$error_message = '';

// Gestione form di aggiornamento profilo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $nome = sanitizeInput($_POST['nome']);
    $cognome = sanitizeInput($_POST['cognome']);
    $email = sanitizeInput($_POST['email']);
    
    try {
        $stmt = $conn->prepare("UPDATE utenti SET nome = :nome, cognome = :cognome, email = :email WHERE id = :id");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cognome', $cognome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            $_SESSION['user_name'] = $nome . ' ' . $cognome;
            $_SESSION['user_email'] = $email;
            $success_message = "Profilo aggiornato con successo!";
        } else {
            $error_message = "Errore durante l'aggiornamento del profilo.";
        }
    } catch(PDOException $e) {
        $error_message = "Errore database: " . $e->getMessage();
    }
}

// Query per ottenere i dati dell'utente
try {
    $stmt = $conn->prepare("SELECT nome, cognome, email FROM utenti WHERE id = :id LIMIT 1");
    $stmt->bindParam(':id', $_SESSION['user_id']);
    $stmt->execute();
    $utente = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error_message = "Errore caricamento profilo: " . $e->getMessage();
    $utente = [];
}
?>

<div class="container-fluid">
    <!-- Profilo Header -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <div class="avatar-wrapper mb-3 mb-md-0">
                                <img src="assets/img/avatar.jpg" alt="Avatar utente" class="img-fluid rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                                <div class="avatar-edit">
                                    <button class="btn btn-sm btn-primary" title="Cambia immagine">
                                        <i class="fas fa-camera"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <h4 class="mb-1"><?php echo $_SESSION['user_name']; ?></h4>
                            <p class="text-muted mb-2">
                                <i class="fas fa-user-tag me-2"></i>
                                <?php echo (hasRole('admin')) ? 'Amministratore' : 'Operatore'; ?>
                            </p>
                            <p class="mb-2"><i class="fas fa-envelope me-2"></i> <?php echo $_SESSION['user_email']; ?></p>
                            <p class="mb-2"><i class="fas fa-phone me-2"></i> +39 333 1234567</p>
                            <p class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Membro dal: 15/01/2022</p>
                        </div>
                        <div class="col-md-3 text-md-end mt-3 mt-md-0">
                            <button class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#modificaProfiloModal"><i class="fas fa-edit me-2"></i> Modifica profilo</button>
                            <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#cambiaPasswordModal"><i class="fas fa-key me-2"></i> Cambia password</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Informazioni personali -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="card-title">Informazioni personali</h6>
                </div>
                <div class="card-body">
                    <form class="row g-3" id="profileForm">
                        <div class="col-md-6">
                            <label for="firstName" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo $utente['nome']; ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="lastName" class="form-label">Cognome</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo $utente['cognome']; ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $utente['email']; ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Telefono</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="+39 333 1234567" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="birthDate" class="form-label">Data di nascita</label>
                            <input type="date" class="form-control" id="birthDate" name="birthDate" value="1985-06-15" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="fiscalCode" class="form-label">Codice Fiscale</label>
                            <input type="text" class="form-control" id="fiscalCode" name="fiscalCode" value="RSSMRA85H15F205Z" readonly>
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label">Indirizzo</label>
                            <input type="text" class="form-control" id="address" name="address" value="Via Roma 123, Milano" readonly>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Impostazioni account -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="card-title">Impostazioni account</h6>
                </div>
                <div class="card-body">
                    <form>
                        <h6 class="mb-3">Preferenze notifiche</h6>
                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="emailNotify" checked>
                            <label class="form-check-label" for="emailNotify">Notifiche via email</label>
                            <div class="form-text">Ricevi una email per aggiornamenti importanti</div>
                        </div>
                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="browserNotify" checked>
                            <label class="form-check-label" for="browserNotify">Notifiche browser</label>
                            <div class="form-text">Mostra notifiche all'interno dell'applicazione</div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h6 class="mb-3">Sicurezza</h6>
                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="twoFactor">
                            <label class="form-check-label" for="twoFactor">Autenticazione a due fattori</label>
                            <div class="form-text">Aggiungi un livello di sicurezza extra al tuo account</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="sessionTimeout" class="form-label">Timeout sessione</label>
                            <select class="form-select" id="sessionTimeout">
                                <option value="15">15 minuti</option>
                                <option value="30" selected>30 minuti</option>
                                <option value="60">1 ora</option>
                                <option value="120">2 ore</option>
                            </select>
                            <div class="form-text">Tempo dopo il quale verrai disconnesso automaticamente</div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h6 class="mb-3">Interfaccia utente</h6>
                        <div class="mb-3">
                            <label for="themeSelect" class="form-label">Tema</label>
                            <select class="form-select" id="themeSelect">
                                <option value="light">Chiaro</option>
                                <option value="dark">Scuro</option>
                                <option value="system" selected>Sistema</option>
                            </select>
                        </div>
                        <div class="text-end mt-4">
                            <button type="button" class="btn btn-primary">Salva impostazioni</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Attività recenti -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Attività recenti</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Attività</th>
                                    <th>IP</th>
                                    <th>Browser</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>15/05/2023 14:30</td>
                                    <td>Login al sistema</td>
                                    <td>192.168.1.100</td>
                                    <td>Chrome 112.0.0.0 (Windows)</td>
                                </tr>
                                <tr>
                                    <td>14/05/2023 16:45</td>
                                    <td>Logout dal sistema</td>
                                    <td>192.168.1.100</td>
                                    <td>Chrome 112.0.0.0 (Windows)</td>
                                </tr>
                                <tr>
                                    <td>14/05/2023 10:20</td>
                                    <td>Modifica profilo</td>
                                    <td>192.168.1.100</td>
                                    <td>Chrome 112.0.0.0 (Windows)</td>
                                </tr>
                                <tr>
                                    <td>13/05/2023 09:15</td>
                                    <td>Registrazione nuovo cliente</td>
                                    <td>192.168.1.100</td>
                                    <td>Chrome 112.0.0.0 (Windows)</td>
                                </tr>
                                <tr>
                                    <td>12/05/2023 11:30</td>
                                    <td>Pagamento ricevuto</td>
                                    <td>192.168.1.100</td>
                                    <td>Chrome 112.0.0.0 (Windows)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Modifica Profilo -->
<div class="modal fade" id="modificaProfiloModal" tabindex="-1" aria-labelledby="modificaProfiloModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modificaProfiloModalLabel">Modifica Profilo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="modificaProfiloForm" method="post" action="">
                    <input type="hidden" name="action" value="update_profile">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $utente['nome']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="cognome" class="form-label">Cognome</label>
                            <input type="text" class="form-control" id="cognome" name="cognome" value="<?php echo $utente['cognome']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $utente['email']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Telefono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" value="+39 333 1234567">
                        </div>
                        <div class="col-md-12">
                            <label for="indirizzo" class="form-label">Indirizzo</label>
                            <input type="text" class="form-control" id="indirizzo" name="indirizzo" value="Via Roma 123, Milano">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="submit" form="modificaProfiloForm" class="btn btn-primary">Salva modifiche</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cambia Password -->
<div class="modal fade" id="cambiaPasswordModal" tabindex="-1" aria-labelledby="cambiaPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cambiaPasswordModalLabel">Cambia Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="cambiaPasswordForm" method="post" action="">
                    <input type="hidden" name="action" value="change_password">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="passwordAttuale" class="form-label">Password Attuale</label>
                            <input type="password" class="form-control" id="passwordAttuale" name="passwordAttuale" required>
                        </div>
                        <div class="col-md-6">
                            <label for="nuovaPassword" class="form-label">Nuova Password</label>
                            <input type="password" class="form-control" id="nuovaPassword" name="nuovaPassword" required>
                        </div>
                        <div class="col-md-6">
                            <label for="confermaPassword" class="form-label">Conferma Password</label>
                            <input type="password" class="form-control" id="confermaPassword" name="confermaPassword" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="submit" form="cambiaPasswordForm" class="btn btn-primary">Cambia Password</button>
            </div>
        </div>
    </div>
</div>
