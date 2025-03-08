<?php
/**
 * Sistema di scheduling per promemoria appuntamenti
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/email.php';

/**
 * Invia promemoria appuntamenti
 */
function sendAppointmentReminders() {
    global $conn;

    // Calcola l'intervallo di tempo per i promemoria (es. 24 ore prima)
    $reminder_time = date('Y-m-d H:i:s', strtotime('+24 hours'));

    try {
        // Query per ottenere gli appuntamenti che devono essere ricordati
        $stmt = $conn->prepare("SELECT a.*, c.nome as cliente_nome, c.cognome as cliente_cognome, c.email as cliente_email
                                FROM appuntamenti a
                                LEFT JOIN clienti c ON a.cliente_id = c.id
                                WHERE a.data = DATE(:reminder_time)
                                AND a.ora BETWEEN TIME(:reminder_time) AND TIME(DATE_ADD(:reminder_time, INTERVAL 1 HOUR))
                                AND a.stato = 'confermato'");

        $stmt->bindParam(':reminder_time', $reminder_time);
        $stmt->execute();
        $appuntamenti = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($appuntamenti as $appuntamento) {
            // Invia email di promemoria
            $subject = "Promemoria Appuntamento - " . SITE_NAME;
            $body = renderEmailTemplate('appointment_reminder', $appuntamento);

            if (sendEmail($appuntamento['cliente_email'], $subject, $body)) {
                // Log dell'invio del promemoria
                logActivity('Promemoria appuntamento inviato', "Appuntamento ID: " . $appuntamento['id'], 'info');
            } else {
                // Log dell'errore nell'invio del promemoria
                logError("Errore nell'invio del promemoria appuntamento", 'error', __FILE__, __LINE__);
            }
        }
    } catch (PDOException $e) {
        logError("Errore durante la ricerca degli appuntamenti per i promemoria: " . $e->getMessage(), 'error', __FILE__, __LINE__);
    }
}

// Esegui la funzione di invio promemoria
sendAppointmentReminders();
?>
