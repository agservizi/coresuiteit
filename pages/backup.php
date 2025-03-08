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

// Gestione backup
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'backup') {
    $backup_file = 'backup_' . date("YmdHis") . '.sql';
    $backup_path = '../backups/' . $backup_file;
    
    // Comando per il backup (MySQLdump)
    $command = "mysqldump --user=" . DB_USER . " --password=" . DB_PASS . " --host=" . DB_HOST . " " . DB_NAME . " > " . $backup_path;
    
    exec($command, $output, $return_var);
    
    if ($return_var == 0) {
        $success_message = "Backup creato con successo: " . $backup_file;
    } else {
        $error_message = "Errore durante il backup del database.";
    }
}

// Gestione ripristino
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'restore') {
    $restore_file = sanitizeInput($_POST['restore_file']);
    $restore_path = '../backups/' . $restore_file;
    
    if (file_exists($restore_path)) {
        // Comando per il ripristino (MySQL)
        $command = "mysql --user=" . DB_USER . " --password=" . DB_PASS . " --host=" . DB_HOST . " " . DB_NAME . " < " . $restore_path;
        
        exec($command, $output, $return_var);
        
        if ($return_var == 0) {
            $success_message = "Ripristino eseguito con successo da: " . $restore_file;
        } else {
            $error_message = "Errore durante il ripristino del database.";
        }
    } else {
        $error_message = "File di backup non trovato.";
    }
}

// Ottieni la lista dei backup
$backup_files = glob('../backups/*.sql');
?>

<div class="container mx-auto">
    <h2>Backup e Ripristino Database</h2>
    
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
    
    <div class="card bg-base-100 shadow-xl mb-6">
        <div class="card-body">
            <h3 class="card-title">Backup Database</h3>
            <p>Esegui un backup completo del database.</p>
            <form method="post" action="">
                <input type="hidden" name="action" value="backup">
                <button type="submit" class="btn btn-primary">Esegui Backup</button>
            </form>
        </div>
    </div>
    
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h3 class="card-title">Ripristino Database</h3>
            <p>Seleziona un file di backup per ripristinare il database.</p>
            
            <form method="post" action="">
                <input type="hidden" name="action" value="restore">
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">File di Backup</span>
                    </label>
                    <select name="restore_file" class="select select-bordered">
                        <?php foreach ($backup_files as $file): ?>
                            <?php $filename = basename($file); ?>
                            <option value="<?php echo $filename; ?>"><?php echo $filename; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">Ripristina Database</button>
                </div>
            </form>
        </div>
    </div>
</div>
