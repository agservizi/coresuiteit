<?php
/**
 * Funzioni per la gestione delle statistiche
 */

/**
 * Ottiene le statistiche generali per la dashboard
 * @return array Un array associativo con tutte le statistiche
 */
function getDashboardStats() {
    global $conn;
    
    try {
        $stats = [];
        
        // Conteggio clienti totali
        $stmt = $conn->query("SELECT COUNT(*) FROM clienti");
        $stats['clienti_totali'] = $stmt->fetchColumn();
        
        // Conteggio clienti nuovi (ultimi 30 giorni)
        $stmt = $conn->query("SELECT COUNT(*) FROM clienti WHERE data_registrazione >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)");
        $stats['clienti_nuovi'] = $stmt->fetchColumn();
        
        // Conteggio pratiche per stato
        $stmt = $conn->query("SELECT stato, COUNT(*) as totale FROM telefonia GROUP BY stato");
        $stats['pratiche_telefonia'] = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $stats['pratiche_telefonia'][$row['stato']] = $row['totale'];
        }
        
        // Conteggio contratti energia per stato
        $stmt = $conn->query("SELECT stato, COUNT(*) as totale FROM energia GROUP BY stato");
        $stats['contratti_energia'] = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $stats['contratti_energia'][$row['stato']] = $row['totale'];
        }
        
        // Somma totale dei pagamenti
        $stmt = $conn->query("SELECT SUM(importo) FROM pagamenti");
        $stats['totale_pagamenti'] = $stmt->fetchColumn() ?: 0;
        
        // Somma pagamenti dell'ultimo mese
        $stmt = $conn->query("SELECT SUM(importo) FROM pagamenti WHERE data_creazione >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)");
        $stats['pagamenti_mese'] = $stmt->fetchColumn() ?: 0;
        
        // Dati per grafico andamento pagamenti
        $stmt = $conn->query("SELECT DATE_FORMAT(data_creazione, '%Y-%m-%d') as data, SUM(importo) as totale 
                             FROM pagamenti 
                             WHERE data_creazione >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
                             GROUP BY DATE_FORMAT(data_creazione, '%Y-%m-%d') 
                             ORDER BY data");
        $stats['grafico_pagamenti'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $stats;
    } catch (PDOException $e) {
        error_log("Errore statistiche: " . $e->getMessage());
        return [];
    }
}

/**
 * Ottiene statistiche di crescita giornaliera, settimanale e mensile
 * @param string $table Nome della tabella
 * @param string $date_column Nome della colonna data
 * @param string $value_column Nome della colonna valore (opzionale, per somme)
 * @return array Array con le statistiche di crescita
 */
function getGrowthStats($table, $date_column, $value_column = null) {
    global $conn;
    
    try {
        $stats = [];
        
        // Costruisci le query in base al tipo di statistica (conteggio o somma)
        $value_select = $value_column ? "SUM($value_column)" : "COUNT(*)";
        
        // Oggi
        $stmt = $conn->query("SELECT $value_select FROM $table WHERE DATE($date_column) = CURDATE()");
        $stats['oggi'] = $stmt->fetchColumn() ?: 0;
        
        // Ieri
        $stmt = $conn->query("SELECT $value_select FROM $table WHERE DATE($date_column) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)");
        $stats['ieri'] = $stmt->fetchColumn() ?: 0;
        
        // Questa settimana
        $stmt = $conn->query("SELECT $value_select FROM $table WHERE YEARWEEK($date_column) = YEARWEEK(CURDATE())");
        $stats['settimana_corrente'] = $stmt->fetchColumn() ?: 0;
        
        // Settimana scorsa
        $stmt = $conn->query("SELECT $value_select FROM $table WHERE YEARWEEK($date_column) = YEARWEEK(DATE_SUB(CURDATE(), INTERVAL 1 WEEK))");
        $stats['settimana_scorsa'] = $stmt->fetchColumn() ?: 0;
        
        // Questo mese
        $stmt = $conn->query("SELECT $value_select FROM $table WHERE YEAR($date_column) = YEAR(CURDATE()) AND MONTH($date_column) = MONTH(CURDATE())");
        $stats['mese_corrente'] = $stmt->fetchColumn() ?: 0;
        
        // Mese scorso
        $stmt = $conn->query("SELECT $value_select FROM $table WHERE YEAR($date_column) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND MONTH($date_column) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))");
        $stats['mese_scorso'] = $stmt->fetchColumn() ?: 0;
        
        // Calcola le variazioni percentuali
        $stats['var_giorno'] = $stats['ieri'] > 0 ? (($stats['oggi'] - $stats['ieri']) / $stats['ieri'] * 100) : 100;
        $stats['var_settimana'] = $stats['settimana_scorsa'] > 0 ? (($stats['settimana_corrente'] - $stats['settimana_scorsa']) / $stats['settimana_scorsa'] * 100) : 100;
        $stats['var_mese'] = $stats['mese_scorso'] > 0 ? (($stats['mese_corrente'] - $stats['mese_scorso']) / $stats['mese_scorso'] * 100) : 100;
        
        return $stats;
    } catch (PDOException $e) {
        error_log("Errore statistiche di crescita: " . $e->getMessage());
        return [];
    }
}

/**
 * Ottiene la distribuzione geografica dei clienti
 * @return array Array con le statistiche di distribuzione
 */
function getGeographicStats() {
    global $conn;
    
    try {
        // Distribuzione per città
        $stmt = $conn->query("SELECT citta, COUNT(*) as totale FROM clienti WHERE citta IS NOT NULL GROUP BY citta ORDER BY totale DESC LIMIT 10");
        $city_stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Distribuzione per CAP
        $stmt = $conn->query("SELECT cap, COUNT(*) as totale FROM clienti WHERE cap IS NOT NULL GROUP BY cap ORDER BY totale DESC LIMIT 10");
        $cap_stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'per_citta' => $city_stats,
            'per_cap' => $cap_stats
        ];
    } catch (PDOException $e) {
        error_log("Errore statistiche geografiche: " . $e->getMessage());
        return [];
    }
}

/**
 * Ottiene le statistiche per uno specifico cliente
 * @param int $cliente_id ID del cliente
 * @return array Array con le statistiche del cliente
 */
function getCustomerStats($cliente_id) {
    global $conn;
    
    try {
        $stats = [];
        
        // Dati base cliente
        $stmt = $conn->prepare("SELECT * FROM clienti WHERE id = :id");
        $stmt->bindParam(':id', $cliente_id);
        $stmt->execute();
        $stats['cliente'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Conteggio contratti
        $stmt = $conn->prepare("SELECT COUNT(*) FROM telefonia WHERE cliente_id = :id");
        $stmt->bindParam(':id', $cliente_id);
        $stmt->execute();
        $stats['num_contratti_telefonia'] = $stmt->fetchColumn();
        
        $stmt = $conn->prepare("SELECT COUNT(*) FROM energia WHERE cliente_id = :id");
        $stmt->bindParam(':id', $cliente_id);
        $stmt->execute();
        $stats['num_contratti_energia'] = $stmt->fetchColumn();
        
        // Somma pagamenti
        $stmt = $conn->prepare("SELECT SUM(importo) FROM pagamenti WHERE cliente_id = :id");
        $stmt->bindParam(':id', $cliente_id);
        $stmt->execute();
        $stats['totale_pagamenti'] = $stmt->fetchColumn() ?: 0;
        
        // Numero documenti
        $stmt = $conn->prepare("SELECT COUNT(*) FROM documenti WHERE cliente_id = :id");
        $stmt->bindParam(':id', $cliente_id);
        $stmt->execute();
        $stats['num_documenti'] = $stmt->fetchColumn();
        
        return $stats;
    } catch (PDOException $e) {
        error_log("Errore statistiche cliente: " . $e->getMessage());
        return [];
    }
}
?>
