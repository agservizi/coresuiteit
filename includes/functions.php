<?php
// Funzione per pulire e sanitizzare gli input
function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Funzione per generare messaggi di alert
function showAlert($message, $type = 'info') {
    return '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">
              ' . $message . '
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
}

// Funzione per verificare se l'utente ha un determinato ruolo
function hasRole($role) {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
}

// Funzione per formattare le date
function formatDate($date) {
    return date('d/m/Y H:i', strtotime($date));
}

// Funzione per generare un ID transazione univoco
function generateTransactionId() {
    return 'TR' . date('YmdHis') . rand(1000, 9999);
}

// Funzione per ottenere l'email del cliente tramite il suo ID
function getClientEmailById($cliente_id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT email FROM clienti WHERE id = :id");
        $stmt->bindParam(':id', $cliente_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['email'] : '';
    } catch (PDOException $e) {
        return '';
    }
}

// Funzione per inviare email di notifica
function sendNotificationEmail($to, $subject, $message) {
    $headers = "From: " . SITE_NAME . " <" . ADMIN_EMAIL . ">\r\n";
    $headers .= "Reply-To: " . ADMIN_EMAIL . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    return mail($to, $subject, $message, $headers);
}

/**
 * Controlla se l'utente è loggato
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Registra un nuovo utente
 * @param string $nome Nome
 * @param string $cognome Cognome
 * @param string $email Email
 * @param string $password Password
 * @param string $ruolo Ruolo
 * @return bool
 */
function registerUser($nome, $cognome, $email, $password, $ruolo = 'operatore') {
    global $conn;
    try {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO utenti (nome, cognome, email, password, ruolo) VALUES (:nome, :cognome, :email, :password, :ruolo)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cognome', $cognome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':ruolo', $ruolo);
        return $stmt->execute();
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Ottiene statistiche per la dashboard
 * @return array
 */
function getDashboardStats() {
    global $conn;
    $stats = [
        'clienti_totali' => 0,
        'clienti_nuovi' => 0,
        'entrate_mese' => 0,
        'percentuale_crescita' => 0,
        'pratiche_completate' => 0,
        'pratiche_in_attesa' => 0
    ];
    
    try {
        // Clienti totali
        $stmt = $conn->query("SELECT COUNT(*) as total FROM clienti");
        $stats['clienti_totali'] = $stmt->fetchColumn();
        
        // Clienti nuovi mese corrente
        $stmt = $conn->query("SELECT COUNT(*) as total FROM clienti WHERE MONTH(data_registrazione) = MONTH(CURRENT_DATE()) AND YEAR(data_registrazione) = YEAR(CURRENT_DATE())");
        $stats['clienti_nuovi'] = $stmt->fetchColumn();
        
        // Entrate del mese
        $stmt = $conn->query("SELECT SUM(importo) as total FROM pagamenti WHERE MONTH(data_creazione) = MONTH(CURRENT_DATE()) AND YEAR(data_creazione) = YEAR(CURRENT_DATE())");
        $stats['entrate_mese'] = $stmt->fetchColumn() ?: 0;
        
        // Percentuale crescita rispetto al mese precedente
        $stmt = $conn->query("SELECT SUM(importo) as total FROM pagamenti WHERE MONTH(data_creazione) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(data_creazione) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)");
        $mese_precedente = $stmt->fetchColumn() ?: 0;
        
        if ($mese_precedente > 0) {
            $stats['percentuale_crescita'] = (($stats['entrate_mese'] - $mese_precedente) / $mese_precedente) * 100;
        }
        
        // Pratiche completate
        $stmt = $conn->query("SELECT COUNT(*) as total FROM telefonia WHERE stato = 'Attivo'");
        $stats['pratiche_completate'] = $stmt->fetchColumn();
        
        // Pratiche in attesa
        $stmt = $conn->query("SELECT COUNT(*) as total FROM telefonia WHERE stato = 'In attivazione'");
        $stats['pratiche_in_attesa'] = $stmt->fetchColumn();
        
        return $stats;
    } catch (PDOException $e) {
        return $stats;
    }
}
?>