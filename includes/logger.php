<?php
/**
 * Sistema di logging per CoreSuite
 */

/**
 * Registra un evento nel log
 * 
 * @param string $action L'azione eseguita
 * @param string $details Dettagli dell'azione
 * @param string $status Stato dell'operazione (success, error, warning, info)
 * @param int $user_id ID dell'utente che ha eseguito l'azione (opzionale)
 * @return bool True se il log è stato registrato, false altrimenti
 */
function logActivity($action, $details = '', $status = 'info', $user_id = null) {
    global $conn;
    
    if (!$user_id && isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    }
    
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $browser = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    
    try {
        $stmt = $conn->prepare("INSERT INTO activity_log (user_id, action, details, status, ip_address, user_agent) 
                              VALUES (:user_id, :action, :details, :status, :ip, :browser)");
                              
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':action', $action);
        $stmt->bindParam(':details', $details);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':browser', $browser);
        
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Errore durante il logging: " . $e->getMessage());
        return false;
    }
}

/**
 * Ottiene gli ultimi log di attività
 * 
 * @param int $limit Numero massimo di log da ottenere
 * @param int $user_id ID dell'utente per filtrare i log (opzionale)
 * @param string $status Stato per filtrare i log (opzionale)
 * @return array Array di log
 */
function getActivityLogs($limit = 100, $user_id = null, $status = null) {
    global $conn;
    
    try {
        $query = "SELECT l.*, u.nome, u.cognome 
                 FROM activity_log l 
                 LEFT JOIN utenti u ON l.user_id = u.id 
                 WHERE 1=1 ";
                 
        $params = [];
        
        if ($user_id !== null) {
            $query .= "AND l.user_id = :user_id ";
            $params[':user_id'] = $user_id;
        }
        
        if ($status !== null) {
            $query .= "AND l.status = :status ";
            $params[':status'] = $status;
        }
        
        $query .= "ORDER BY l.timestamp DESC LIMIT :limit";
        $params[':limit'] = $limit;
        
        $stmt = $conn->prepare($query);
        
        foreach ($params as $key => $value) {
            if ($key === ':limit') {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value);
            }
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Errore durante il recupero dei log: " . $e->getMessage());
        return [];
    }
}

/**
 * Registra un errore
 * 
 * @param string $message Messaggio di errore
 * @param string $severity Gravità dell'errore (error, warning, critical)
 * @param string $file Il file in cui si è verificato l'errore
 * @param int $line La linea in cui si è verificato l'errore
 * @return bool True se l'errore è stato registrato, false altrimenti
 */
function logError($message, $severity = 'error', $file = '', $line = 0) {
    global $conn;
    
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
    $source = '';
    
    // Se viene fornito il file e la linea, usali per costruire la sorgente
    if ($file && $line) {
        $source = "$file:$line";
    } 
    // Altrimenti cerca di ottenere il file e la linea dal backtrace
    else if (!empty($trace[1]['file'])) {
        $source = $trace[1]['file'] . ':' . $trace[1]['line'];
    }
    
    try {
        $stmt = $conn->prepare("INSERT INTO error_log (user_id, message, severity, source, ip_address) 
                              VALUES (:user_id, :message, :severity, :source, :ip)");
                              
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':severity', $severity);
        $stmt->bindParam(':source', $source);
        $stmt->bindParam(':ip', $ip);
        
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Errore durante il logging dell'errore: " . $e->getMessage());
        error_log("Errore originale: $severity - $message - $source");
        return false;
    }
}

/**
 * Handler personalizzato per gli errori PHP
 * 
 * @param int $errno Livello dell'errore
 * @param string $errstr Messaggio di errore
 * @param string $errfile File in cui si è verificato l'errore
 * @param int $errline Linea in cui si è verificato l'errore
 * @return bool
 */
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    $severity = 'error';
    
    switch ($errno) {
        case E_ERROR:
        case E_USER_ERROR:
            $severity = 'critical';
            break;
        case E_WARNING:
        case E_USER_WARNING:
            $severity = 'warning';
            break;
        case E_NOTICE:
        case E_USER_NOTICE:
            $severity = 'notice';
            break;
    }
    
    logError($errstr, $severity, $errfile, $errline);
    
    // Non interrompere l'esecuzione dello script
    return false;
}

// Registra l'handler personalizzato per gli errori
set_error_handler('customErrorHandler');
?>
