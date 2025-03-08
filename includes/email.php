<?php
require_once __DIR__ . '/../config/email.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Invia un'email utilizzando PHPMailer
 * 
 * @param string $to Destinatario email
 * @param string $subject Oggetto dell'email
 * @param string $body Corpo dell'email (HTML)
 * @param array $attachments Array di percorsi di file da allegare
 * @param array $cc Array di indirizzi email in CC
 * @param array $bcc Array di indirizzi email in BCC
 * @return bool True se l'invio è avvenuto con successo, altrimenti false
 */
function sendEmail($to, $subject, $body, $attachments = [], $cc = [], $bcc = []) {
    try {
        $mail = new PHPMailer(true);
        
        // Impostazioni server
        $mail->SMTPDebug = EMAIL_DEBUG;
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = SMTP_AUTH;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        $mail->CharSet = EMAIL_CHARSET;
        
        // Destinatari
        $mail->setFrom(EMAIL_FROM, NAME_FROM);
        $mail->addAddress($to);
        
        // CC
        foreach ($cc as $ccEmail) {
            $mail->addCC($ccEmail);
        }
        
        // BCC
        foreach ($bcc as $bccEmail) {
            $mail->addBCC($bccEmail);
        }
        
        // Allegati
        foreach ($attachments as $attachment) {
            if (file_exists($attachment)) {
                $mail->addAttachment($attachment);
            }
        }
        
        // Contenuto
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $body));
        
        return $mail->send();
    } catch (Exception $e) {
        error_log('Errore nell\'invio dell\'email: ' . $mail->ErrorInfo);
        return false;
    }
}

/**
 * Genera un'email da un template
 * 
 * @param string $templateName Nome del file template senza estensione
 * @param array $data Dati da sostituire nel template
 * @return string Contenuto HTML dell'email
 */
function renderEmailTemplate($templateName, $data = []) {
    $templateFile = EMAIL_TEMPLATE_PATH . $templateName . '.php';
    
    if (!file_exists($templateFile)) {
        error_log('Template email non trovato: ' . $templateFile);
        return '';
    }
    
    ob_start();
    extract($data);
    include $templateFile;
    return ob_get_clean();
}

/**
 * Invia notifica di registrazione nuovo cliente
 * 
 * @param string $email Email del destinatario
 * @param array $clientData Dati del cliente
 * @return bool
 */
function sendNewClientNotification($email, $clientData) {
    $subject = 'Benvenuto su CoreSuite';
    $body = renderEmailTemplate('welcome_client', $clientData);
    return sendEmail($email, $subject, $body);
}

/**
 * Invia notifica di registrazione nuovo contratto
 * 
 * @param string $email Email del destinatario
 * @param array $contractData Dati del contratto
 * @return bool
 */
function sendNewContractNotification($email, $contractData) {
    $subject = 'Nuovo Contratto Registrato';
    $body = renderEmailTemplate('new_contract', $contractData);
    return sendEmail($email, $subject, $body);
}

/**
 * Invia email di recupero password
 * 
 * @param string $email Email del destinatario
 * @param string $resetLink Link per il reset della password
 * @return bool
 */
function sendPasswordResetEmail($email, $resetLink) {
    $subject = 'Recupero password - CoreSuite';
    $body = renderEmailTemplate('password_reset', ['resetLink' => $resetLink]);
    return sendEmail($email, $subject, $body);
}
