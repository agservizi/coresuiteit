<?php
/**
 * Sistema di audit log
 */

/**
 * Registra un evento nell'audit log
 * @param string $action Azione eseguita
 * @param string $details Dettagli dell'azione
 * @param string $table Tabella coinvolta
 * @param int $record_id ID del record coinvolto
 * @return bool
 */
function logAudit($action, $details, $table, $record_id = null) {
    global $conn;
    
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    
    try {
        $stmt = $conn->prepare("INSERT INTO audit_log (user_id, action, details, table_name, record_id, ip_address, timestamp) 
                              VALUES (:user_id, :action, :details, :table_name, :record_id, :ip, NOW())");
                              
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':action', $action);
        $stmt->bindParam(':details', $details);
        $stmt->bindParam(':table_name', $table);
        $stmt->bindParam(':record_id', $record_id);
        $stmt->bindParam(':ip', $ip);
        
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Errore durante il logging dell'audit: " . $e->getMessage());
        return false;
    }
}

/**
 * Ottiene gli ultimi log di audit
 * @param int $limit Numero massimo di log da ottenere
 * @return array Array di log
 */
function getAuditLogs($limit = 100) {
    global $conn;
    
    try {
        $stmt = $conn->query("SELECT l.*, u.nome, u.cognome 
                             FROM audit_log l 
                             LEFT JOIN utenti u ON l.user_id = u.id 
                             ORDER BY l.timestamp DESC LIMIT $limit");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Errore durante il recupero dei log di audit: " . $e->getMessage());
        return [];
    }
}
