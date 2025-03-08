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
                            <p class="mb-2"><i class="fas fa-envelope me-2"></i> <?php echo $utente['email']; ?></p>
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
    
    <!-- ...existing code... -->
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
