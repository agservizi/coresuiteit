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
    return uniqid('TXN');
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
 * Funzione per inviare email di notifica
 * @param string $email Email del destinatario
 * @param string $subject Oggetto dell'email
 * @param string $message Messaggio dell'email
 * @return bool
 */
function sendNotificationEmail($email, $subject, $message) {
    // Utilizza la funzione sendEmail per inviare la notifica
    return sendEmail($email, $subject, $message);
}

/**
 * Funzione per ottenere l'email del cliente tramite ID
 * @param int $cliente_id ID del cliente
 * @return string|null
 */
function getClientEmailById($cliente_id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT email FROM clienti WHERE id = :cliente_id");
        $stmt->bindParam(':cliente_id', $cliente_id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
            return $cliente['email'];
        } else {
            return null;
        }
    } catch (PDOException $e) {
        error_log("Errore recupero email cliente: " . $e->getMessage());
        return null;
    }
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

/**
 * Crea una notifica per un utente
 * @param int $user_id ID dell'utente destinatario
 * @param string $tipo Tipo di notifica
 * @param string $messaggio Messaggio della notifica
 * @param string $link URL relativo per la notifica
 * @return bool
 */
function creaNotifica($user_id, $tipo, $messaggio, $link = '') {
    global $conn;
    try {
        $stmt = $conn->prepare("INSERT INTO notifiche (utente_id, tipo, messaggio, link, data_creazione, letta) 
                               VALUES (:utente_id, :tipo, :messaggio, :link, NOW(), 0)");
        $stmt->bindParam(':utente_id', $user_id);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':messaggio', $messaggio);
        $stmt->bindParam(':link', $link);
        return $stmt->execute();
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Ottiene le notifiche non lette di un utente
 * @param int $user_id ID dell'utente
 * @param int $limit Numero massimo di notifiche da ottenere
 * @return array
 */
function getNotificheNonLette($user_id, $limit = 10) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM notifiche WHERE utente_id = :user_id AND letta = 0 ORDER BY data_creazione DESC LIMIT :limit");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Errore recupero notifiche non lette: " . $e->getMessage());
        return [];
    }
}

/**
 * Marca una notifica come letta
 * @param int $notifica_id ID della notifica
 * @return bool
 */
function marcaNotificaLetta($notifica_id) {
    global $conn;
    try {
        $stmt = $conn->prepare("UPDATE notifiche SET letta = 1 WHERE id = :id");
        $stmt->bindParam(':id', $notifica_id, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Errore marcatura notifica come letta: " . $e->getMessage());
        return false;
    }
}

/**
 * Funzione per generare un token di recupero password
 * @param string $email Email dell'utente
 * @return string|bool Il token generato o false in caso di errore
 */
function generaTokenRecupero($email) {
    global $conn;
    try {
        // Verifica se l'email esiste
        $stmt = $conn->prepare("SELECT id FROM utenti WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() == 0) {
            return false;
        }
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $user_id = $user['id'];
        
        // Genera token
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+24 hours'));
        
        // Elimina vecchi token dell'utente
        $stmt = $conn->prepare("DELETE FROM password_reset WHERE utente_id = :utente_id");
        $stmt->bindParam(':utente_id', $user_id);
        $stmt->execute();
        
        // Salva nuovo token
        $stmt = $conn->prepare("INSERT INTO password_reset (utente_id, token, scadenza) VALUES (:utente_id, :token, :scadenza)");
        $stmt->bindParam(':utente_id', $user_id);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':scadenza', $expires);
        $stmt->execute();
        
        return $token;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Verifica la validità di un token di recupero password
 * @param string $token Il token da verificare
 * @return int|bool ID dell'utente se valido, false altrimenti
 */
function verificaTokenRecupero($token) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT utente_id FROM password_reset WHERE token = :token AND scadenza > NOW() LIMIT 1");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        
        if ($stmt->rowCount() == 0) {
            return false;
        }
        
        $reset = $stmt->fetch(PDO::FETCH_ASSOC);
        return $reset['utente_id'];
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Resetta la password di un utente
 * @param int $user_id ID dell'utente
 * @param string $password Nuova password
 * @param string $token Token di recupero
 * @return bool
 */
function resetPassword($user_id, $password, $token) {
    global $conn;
    try {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("UPDATE utenti SET password = :password WHERE id = :id");
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':id', $user_id);
        $result = $stmt->execute();
        
        if ($result) {
            // Elimina il token utilizzato
            $stmt = $conn->prepare("DELETE FROM password_reset WHERE token = :token");
            $stmt->bindParam(':token', $token);
            $stmt->execute();
            return true;
        }
        
        return false;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Carica un file nel sistema
 * @param array $file File da caricare ($_FILES['nome'])
 * @param string $tipo Tipo di documento
 * @param int $cliente_id ID del cliente associato
 * @return string|bool Nome del file caricato o false in caso di errore
 */
function caricaFile($file, $tipo, $cliente_id) {
    // Directory per i documenti
    $upload_dir = '../uploads/';
    $cliente_dir = $upload_dir . 'cliente_' . $cliente_id . '/';
    
    // Crea directory se non esiste
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755);
    }
    if (!file_exists($cliente_dir)) {
        mkdir($cliente_dir, 0755);
    }
    
    // Controlla estensione
    $allowed_ext = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($ext, $allowed_ext)) {
        return false;
    }
    
    // Genera nome file unico
    $new_filename = $tipo . '_' . date('YmdHis') . '_' . uniqid() . '.' . $ext;
    $destination = $cliente_dir . $new_filename;
    
    // Carica file
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return $new_filename;
    }
    
    return false;
}

/**
 * Registra un documento nel database
 * @param string $nome Nome del documento
 * @param string $filename Nome del file sul server
 * @param int $cliente_id ID cliente associato
 * @param string $tipo Tipo documento
 * @param string $note Note aggiuntive
 * @return bool
 */
function registraDocumento($nome, $filename, $cliente_id, $tipo, $note = '') {
    global $conn;
    try {
        $stmt = $conn->prepare("INSERT INTO documenti (cliente_id, nome, filename, tipo, note, data_caricamento) 
                              VALUES (:cliente_id, :nome, :filename, :tipo, :note, NOW())");
        $stmt->bindParam(':cliente_id', $cliente_id);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':filename', $filename);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':note', $note);
        return $stmt->execute();
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Sanitize user input
 * @param string $data
 * @return string
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Format date
 * @param string $date
 * @return string
 */
function formatDate($date) {
    return date('d/m/Y H:i:s', strtotime($date));
}

/**
 * Genera un token di recupero password
 * @param string $email
 * @return string|bool
 */
function generaTokenRecupero($email) {
    global $conn;
    
    try {
        // Verifica se l'email esiste
        $stmt = $conn->prepare("SELECT id FROM utenti WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() == 0) {
            return false;
        }
        
        $utente = $stmt->fetch(PDO::FETCH_ASSOC);
        $utente_id = $utente['id'];
        
        // Genera token univoco
        $token = bin2hex(random_bytes(32));
        
        // Calcola la scadenza (24 ore)
        $scadenza = date('Y-m-d H:i:s', strtotime('+24 hours'));
        
        // Inserisci il token nel database
        $stmt = $conn->prepare("INSERT INTO password_reset (utente_id, token, scadenza) VALUES (:utente_id, :token, :scadenza)");
        $stmt->bindParam(':utente_id', $utente_id);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':scadenza', $scadenza);
        
        if ($stmt->execute()) {
            return $token;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        error_log("Errore generazione token: " . $e->getMessage());
        return false;
    }
}

/**
 * Verifica la validità di un token di recupero password
 * @param string $token
 * @return int|bool L'ID dell'utente o false se il token non è valido
 */
function verificaTokenRecupero($token) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT utente_id, scadenza FROM password_reset WHERE token = :token");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        
        if ($stmt->rowCount() == 0) {
            return false;
        }
        
        $reset = $stmt->fetch(PDO::FETCH_ASSOC);
        $scadenza = $reset['scadenza'];
        $utente_id = $reset['utente_id'];
        
        // Verifica se il token è scaduto
        if (strtotime($scadenza) < time()) {
            return false;
        }
        
        return $utente_id;
    } catch (PDOException $e) {
        error_log("Errore verifica token: " . $e->getMessage());
        return false;
    }
}

/**
 * Reimposta la password di un utente
 * @param int $utente_id
 * @param string $password
 * @param string $token
 * @return bool
 */
function resetPassword($utente_id, $password, $token) {
    global $conn;
    
    try {
        // Hash della nuova password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Aggiorna la password dell'utente
        $stmt = $conn->prepare("UPDATE utenti SET password = :password WHERE id = :utente_id");
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':utente_id', $utente_id);
        
        if ($stmt->execute()) {
            // Elimina il token di recupero
            $stmt = $conn->prepare("DELETE FROM password_reset WHERE token = :token");
            $stmt->bindParam(':token', $token);
            $stmt->execute();
            
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        error_log("Errore reset password: " . $e->getMessage());
        return false;
    }
}

/**
 * Registra un nuovo utente
 * @param string $nome
 * @param string $cognome
 * @param string $email
 * @param string $password
 * @param string $ruolo
 * @return bool
 */
function registerUser($nome, $cognome, $email, $password, $ruolo = 'operatore') {
    global $conn;
    
    try {
        // Hash della password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Inserisci l'utente nel database
        $stmt = $conn->prepare("INSERT INTO utenti (nome, cognome, email, password, ruolo) 
                              VALUES (:nome, :cognome, :email, :password, :ruolo)");
        
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cognome', $cognome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':ruolo', $ruolo);
        
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Errore registrazione utente: " . $e->getMessage());
        return false;
    }
}

/**
 * Esegue il login di un utente
 * @param string $email
 * @param string $password
 * @return bool
 */
function loginUser($email, $password) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT id, nome, cognome, password, ruolo FROM utenti WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $utente = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (password_verify($password, $utente['password'])) {
                // Avvia la sessione e imposta le variabili
                $_SESSION['user_id'] = $utente['id'];
                $_SESSION['user_name'] = $utente['nome'] . ' ' . $utente['cognome'];
                $_SESSION['user_email'] = $email;
                $_SESSION['user_role'] = $utente['ruolo'];
                
                // Rigenera l'ID di sessione per sicurezza
                session_regenerate_id(true);
                
                // Registra l'accesso nel log
                logActivity('Login', 'Accesso effettuato con successo', 'success', $utente['id']);
                
                return true;
            }
        }
        
        // Registra il tentativo di accesso fallito
        logActivity('Login Fallito', "Tentativo di accesso fallito per l'email: $email", 'warning');
        
        return false;
    } catch (PDOException $e) {
        error_log("Errore login: " . $e->getMessage());
        return false;
    }
}

/**
 * Esegue il logout di un utente
 */
function logoutUser() {
    // Registra il logout nel log
    logActivity('Logout', 'Logout effettuato', 'info', $_SESSION['user_id'] ?? null);
    
    // Pulisci tutte le variabili di sessione
    $_SESSION = [];
    
    // Cancella il cookie di sessione
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Distrugge la sessione
    session_destroy();
}
?>