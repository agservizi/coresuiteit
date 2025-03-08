<?php
/**
 * Sistema di gestione delle API keys
 */

/**
 * Genera una nuova API key
 * @param int $user_id ID dell'utente a cui assegnare la chiave
 * @return string|bool La chiave generata o false in caso di errore
 */
function generateApiKey($user_id) {
    global $conn;
    
    $api_key = bin2hex(random_bytes(32));
    $hashed_key = password_hash($api_key, PASSWORD_DEFAULT);
    
    try {
        $stmt = $conn->prepare("INSERT INTO api_keys (user_id, api_key, data_creazione) 
                              VALUES (:user_id, :api_key, NOW())");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':api_key', $hashed_key);
        
        if ($stmt->execute()) {
            return $api_key;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        error_log("Errore durante la generazione della API key: " . $e->getMessage());
        return false;
    }
}

/**
 * Verifica la validità di una API key
 * @param string $api_key La API key da verificare
 * @return int|bool L'ID dell'utente a cui appartiene la chiave o false se non valida
 */
function verifyApiKey($api_key) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT user_id, api_key FROM api_keys");
        $stmt->execute();
        $apiKeys = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($apiKeys as $apiKeyData) {
            if (password_verify($api_key, $apiKeyData['api_key'])) {
                return (int)$apiKeyData['user_id'];
            }
        }

        return false;
    } catch (PDOException $e) {
        error_log("Errore durante la verifica della API key: " . $e->getMessage());
        return false;
    }
}

/**
 * Revoca una API key
 * @param string $api_key La API key da revocare
 * @return bool
 */
function revokeApiKey($api_key) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("DELETE FROM api_keys WHERE api_key = :api_key");
        $stmt->bindParam(':api_key', $api_key);
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Errore durante la revoca della API key: " . $e->getMessage());
        return false;
    }
}
