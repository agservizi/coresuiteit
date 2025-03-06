<?php
// Funzioni per la gestione dell'autenticazione

// Verifica se l'utente è loggato
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
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
