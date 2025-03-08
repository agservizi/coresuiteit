<?php
// Funzione per pulire e sanitizzare gli input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
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
    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == $role) {
        return true;
    }
    return false;
}

// Funzione per formattare le date
function formatDate($date, $format = 'd/m/Y H:i') {
    $dateObj = new DateTime($date);
    return $dateObj->format($format);
}

// Funzione per generare un ID transazione univoco
function generateTransactionId() {
    return 'TR' . date('YmdHis') . rand(1000, 9999);
}

// Funzione per ottenere l'email del cliente tramite il suo ID
function getClientEmailById($cliente_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT email FROM clienti WHERE id = :id LIMIT 1");
    $stmt->bindParam(':id', $cliente_id);
    $stmt->execute();
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    return $cliente['email'];
}

// Funzione per inviare email di notifica
function sendNotificationEmail($to, $subject, $message) {
    $headers = "From: no-reply@agenziaservizi.com\r\n";
    $headers .= "Reply-To: no-reply@agenziaservizi.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    return mail($to, $subject, $message, $headers);
}
?>