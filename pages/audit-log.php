<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
require_once '../includes/audit.php';

// Controllo permessi
if (!isLoggedIn() || !hasRole('admin')) {
    header("Location: login.php");
    exit;
}

// Ottieni i log di audit
$audit_logs = getAuditLogs(100);
?>

<div class="container mx-auto">
    <h2>Audit Log</h2>
    <table class="table table-zebra">
        <thead>
            <tr>
                <th>ID</th>
                <th>Utente</th>
                <th>Azione</th>
                <th>Dettagli</th>
                <th>Tabella</th>
                <th>Record ID</th>
                <th>IP Address</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($audit_logs)): ?>
                <tr>
                    <td colspan="8" class="text-center">Nessun log trovato</td>
                </tr>
            <?php else: ?>
                <?php foreach ($audit_logs as $log): ?>
                    <tr>
                        <td><?php echo $log['id']; ?></td>
                        <td><?php echo $log['nome'] . ' ' . $log['cognome']; ?></td>
                        <td><?php echo $log['action']; ?></td>
                        <td><?php echo $log['details']; ?></td>
                        <td><?php echo $log['table_name']; ?></td>
                        <td><?php echo $log['record_id']; ?></td>
                        <td><?php echo $log['ip_address']; ?></td>
                        <td><?php echo formatDate($log['timestamp']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
