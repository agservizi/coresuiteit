<?php
/**
 * Funzioni per la gestione dei documenti
 */

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
 * Ottiene i documenti di un cliente
 * @param int $cliente_id ID del cliente
 * @return array
 */
function getClientDocuments($cliente_id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM documenti WHERE cliente_id = :cliente_id ORDER BY data_caricamento DESC");
        $stmt->bindParam(':cliente_id', $cliente_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Errore recupero documenti cliente: " . $e->getMessage());
        return [];
    }
}

/**
 * Elimina un documento
 * @param int $documento_id ID del documento
 * @return bool
 */
function deleteDocument($documento_id) {
    global $conn;
    try {
        // Ottieni il nome del file prima di eliminarlo
        $stmt = $conn->prepare("SELECT filename, cliente_id FROM documenti WHERE id = :id");
        $stmt->bindParam(':id', $documento_id);
        $stmt->execute();
        $documento = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($documento) {
            $filename = $documento['filename'];
            $cliente_id = $documento['cliente_id'];
            $filepath = '../uploads/cliente_' . $cliente_id . '/' . $filename;
            
            // Elimina il file dal filesystem
            if (file_exists($filepath)) {
                unlink($filepath);
            }
            
            // Elimina il record dal database
            $stmt = $conn->prepare("DELETE FROM documenti WHERE id = :id");
            $stmt->bindParam(':id', $documento_id);
            return $stmt->execute();
        } else {
            return false;
        }
    } catch (PDOException $e) {
        error_log("Errore eliminazione documento: " . $e->getMessage());
        return false;
    }
}
