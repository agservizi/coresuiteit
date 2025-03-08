<?php
/**
 * Gestione appuntamenti
 */

/**
 * Crea un nuovo appuntamento
 * 
 * @param int $cliente_id ID del cliente
 * @param string $titolo Titolo dell'appuntamento
 * @param string $descrizione Descrizione dell'appuntamento
 * @param string $data Data dell'appuntamento (formato Y-m-d)
 * @param string $ora Ora dell'appuntamento (formato H:i:s)
 * @param int $durata Durata in minuti
 * @param string $stato Stato dell'appuntamento (confermato, in attesa, cancellato)
 * @param int $utente_id ID dell'utente che gestisce l'appuntamento
 * @return int|bool ID del nuovo appuntamento o false in caso di errore
 */
function createAppointment($cliente_id, $titolo, $descrizione, $data, $ora, $durata = 60, $stato = 'in attesa', $utente_id = null) {
    global $conn;
    
    if (!$utente_id && isset($_SESSION['user_id'])) {
        $utente_id = $_SESSION['user_id'];
    }
    
    try {
        $stmt = $conn->prepare("INSERT INTO appuntamenti (cliente_id, utente_id, titolo, descrizione, data, ora, durata, stato, data_creazione) 
                              VALUES (:cliente_id, :utente_id, :titolo, :descrizione, :data, :ora, :durata, :stato, NOW())");
        
        $stmt->bindParam(':cliente_id', $cliente_id);
        $stmt->bindParam(':utente_id', $utente_id);
        $stmt->bindParam(':titolo', $titolo);
        $stmt->bindParam(':descrizione', $descrizione);
        $stmt->bindParam(':data', $data);
        $stmt->bindParam(':ora', $ora);
        $stmt->bindParam(':durata', $durata);
        $stmt->bindParam(':stato', $stato);
        
        if ($stmt->execute()) {
            return $conn->lastInsertId();
        } else {
            return false;
        }
    } catch (PDOException $e) {
        error_log("Errore creazione appuntamento: " . $e->getMessage());
        return false;
    }
}

/**
 * Ottiene un appuntamento per ID
 * 
 * @param int $id ID dell'appuntamento
 * @return array|bool Dati dell'appuntamento o false in caso di errore
 */
function getAppointment($id) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT a.*, c.nome as cliente_nome, c.cognome as cliente_cognome, 
                               u.nome as utente_nome, u.cognome as utente_cognome
                               FROM appuntamenti a
                               LEFT JOIN clienti c ON a.cliente_id = c.id
                               LEFT JOIN utenti u ON a.utente_id = u.id
                               WHERE a.id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    } catch (PDOException $e) {
        error_log("Errore recupero appuntamento: " . $e->getMessage());
        return false;
    }
}

/**
 * Aggiorna un appuntamento esistente
 * 
 * @param int $id ID dell'appuntamento
 * @param array $data Dati da aggiornare
 * @return bool True se l'aggiornamento ha avuto successo, altrimenti False
 */
function updateAppointment($id, $data) {
    global $conn;
    
    // Campi permessi per l'aggiornamento
    $allowed_fields = [
        'cliente_id', 'utente_id', 'titolo', 'descrizione', 
        'data', 'ora', 'durata', 'stato'
    ];
    
    // Filtra i dati per includere solo i campi permessi
    $update_data = array_intersect_key($data, array_flip($allowed_fields));
    
    if (empty($update_data)) {
        return false; // Nessun dato da aggiornare
    }
    
    try {
        // Costruisci la query di aggiornamento
        $sql_parts = [];
        $params = [':id' => $id];
        
        foreach ($update_data as $key => $value) {
            $sql_parts[] = "$key = :$key";
            $params[":$key"] = $value;
        }
        
        $sql = "UPDATE appuntamenti SET " . implode(", ", $sql_parts) . " WHERE id = :id";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute($params);
    } catch (PDOException $e) {
        error_log("Errore aggiornamento appuntamento: " . $e->getMessage());
        return false;
    }
}

/**
 * Elimina un appuntamento
 * 
 * @param int $id ID dell'appuntamento
 * @return bool True se l'eliminazione ha avuto successo, altrimenti False
 */
function deleteAppointment($id) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("DELETE FROM appuntamenti WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Errore eliminazione appuntamento: " . $e->getMessage());
        return false;
    }
}

/**
 * Ottiene gli appuntamenti per un determinato giorno
 * 
 * @param string $date Data in formato Y-m-d
 * @param int $utente_id ID dell'utente (opzionale)
 * @return array Array di appuntamenti
 */
function getAppointmentsByDay($date, $utente_id = null) {
    global $conn;
    
    try {
        $sql = "SELECT a.*, c.nome as cliente_nome, c.cognome as cliente_cognome
                FROM appuntamenti a
                LEFT JOIN clienti c ON a.cliente_id = c.id
                WHERE a.data = :data";
                
        $params = [':data' => $date];
        
        if ($utente_id) {
            $sql .= " AND a.utente_id = :utente_id";
            $params[':utente_id'] = $utente_id;
        }
        
        $sql .= " ORDER BY a.ora ASC";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Errore recupero appuntamenti per giorno: " . $e->getMessage());
        return [];
    }
}

/**
 * Ottiene gli appuntamenti per un intervallo di date
 * 
 * @param string $start_date Data iniziale in formato Y-m-d
 * @param string $end_date Data finale in formato Y-m-d
 * @param int $utente_id ID dell'utente (opzionale)
 * @return array Array di appuntamenti
 */
function getAppointmentsByRange($start_date, $end_date, $utente_id = null) {
    global $conn;
    
    try {
        $sql = "SELECT a.*, c.nome as cliente_nome, c.cognome as cliente_cognome
                FROM appuntamenti a
                LEFT JOIN clienti c ON a.cliente_id = c.id
                WHERE a.data BETWEEN :start_date AND :end_date";
                
        $params = [':start_date' => $start_date, ':end_date' => $end_date];
        
        if ($utente_id) {
            $sql .= " AND a.utente_id = :utente_id";
            $params[':utente_id'] = $utente_id;
        }
        
        $sql .= " ORDER BY a.data ASC, a.ora ASC";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Errore recupero appuntamenti per intervallo: " . $e->getMessage());
        return [];
    }
}

/**
 * Verifica se ci sono sovrapposizioni di appuntamenti
 * 
 * @param string $data Data dell'appuntamento (formato Y-m-d)
 * @param string $ora Ora di inizio (formato H:i:s)
 * @param int $durata Durata in minuti
 * @param int $utente_id ID dell'utente
 * @param int $exclude_id ID dell'appuntamento da escludere (per aggiornamenti)
 * @return bool True se ci sono sovrapposizioni, altrimenti False
 */
function hasAppointmentOverlap($data, $ora, $durata, $utente_id, $exclude_id = null) {
    global $conn;
    
    // Calcola l'ora di fine dell'appuntamento
    $ora_datetime = new DateTime($ora);
    $ora_fine = clone $ora_datetime;
    $ora_fine->add(new DateInterval('PT' . $durata . 'M'));
    $ora_fine = $ora_fine->format('H:i:s');
    
    try {
        $sql = "SELECT COUNT(*) FROM appuntamenti 
                WHERE utente_id = :utente_id 
                AND data = :data 
                AND ((ora <= :ora AND ADDTIME(ora, SEC_TO_TIME(durata*60)) > :ora) 
                    OR (ora < :ora_fine AND ADDTIME(ora, SEC_TO_TIME(durata*60)) >= :ora_fine)
                    OR (ora >= :ora AND ADDTIME(ora, SEC_TO_TIME(durata*60)) <= :ora_fine))";
                    
        $params = [
            ':utente_id' => $utente_id,
            ':data' => $data,
            ':ora' => $ora,
            ':ora_fine' => $ora_fine
        ];
        
        if ($exclude_id) {
            $sql .= " AND id != :exclude_id";
            $params[':exclude_id'] = $exclude_id;
        }
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("Errore controllo sovrapposizione appuntamenti: " . $e->getMessage());
        return false;
    }
}
