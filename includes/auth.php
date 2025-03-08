<?php
/**
 * Funzioni di autenticazione e autorizzazione
 */

/**
 * Controlla se l'utente è loggato
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

if (!function_exists('getUserRole')) {
    /**
     * Ottiene il ruolo dell'utente corrente
     * @return string|null Il ruolo dell'utente o null se non è loggato
     */
    function getUserRole() {
        return isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
    }
}

/**
 * Verifica se l'utente ha un permesso specifico
 * @param string $permission Il permesso da verificare
 * @return bool
 */
function hasPermission($permission) {
    global $conn;
    
    if (!isLoggedIn()) {
        return false;
    }
    
    $user_id = $_SESSION['user_id'];
    $user_role = $_SESSION['user_role'];
    
    // Gli amministratori hanno tutti i permessi
    if ($user_role === 'admin') {
        return true;
    }
    
    try {
        // Query per verificare se l'utente ha il permesso
        $stmt = $conn->prepare("SELECT COUNT(*) FROM permessi_ruoli pr
                                INNER JOIN permessi p ON pr.permesso_id = p.id
                                INNER JOIN utenti u ON u.ruolo = pr.ruolo
                                WHERE u.id = :user_id AND p.nome = :permission");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':permission', $permission);
        $stmt->execute();
        
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("Errore verifica permesso: " . $e->getMessage());
        return false;
    }
}

/**
 * Reindirizza l'utente se non ha il permesso richiesto
 * @param string $permission Il permesso necessario per accedere
 * @param string $redirectUrl URL verso cui reindirizzare in caso di permessi insufficienti
 * @return void
 */
function requirePermission($permission, $redirectUrl = 'index.php') {
    if (!hasPermission($permission)) {
        header("Location: $redirectUrl");
        exit;
    }
}

/**
 * Verifica se l'utente ha un ruolo specifico
 * @param string $role Il ruolo da verificare
 * @return bool
 */
function hasRole($role) {
    return isLoggedIn() && $_SESSION['user_role'] === $role;
}

/**
 * Reindirizza l'utente se non ha il ruolo richiesto
 * @param string $role Il ruolo necessario per accedere
 * @param string $redirectUrl URL verso cui reindirizzare in caso di ruoli insufficienti
 * @return void
 */
function requireRole($role, $redirectUrl = 'index.php') {
    if (!hasRole($role)) {
        header("Location: $redirectUrl");
        exit;
    }
}

/**
 * Verifica che l'utente sia un amministratore
 * @return bool
 */
function isAdmin() {
    return isLoggedIn() && $_SESSION['user_role'] === 'admin';
}

/**
 * Registra i tentativi di accesso falliti
 * @param string $email Email utilizzata per il tentativo
 * @return void
 */
function logFailedLoginAttempt($email) {
    // Implementazione della registrazione del tentativo fallito
}

// Funzione login
function loginUser($email, $password) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT id, nome, cognome, email, password, ruolo FROM utenti WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nome'] . ' ' . $user['cognome'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['ruolo'];
            return true;
        }
    }
    return false;
}

// Funzione logout
function logoutUser() {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

// Funzione per registrare un nuovo utente
function registerUser($nome, $cognome, $email, $password, $ruolo = 'operatore') {
    global $conn;
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO utenti (nome, cognome, email, password, ruolo) VALUES (:nome, :cognome, :email, :password, :ruolo)");
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cognome', $cognome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':ruolo', $ruolo);
    
    return $stmt->execute();
}
?>
