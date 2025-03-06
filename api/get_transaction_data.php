<?php
require_once '../config/config.php';

try {
    $stmt = $conn->query("SELECT DATE(data_creazione) AS data, COUNT(*) AS numero_transazioni FROM pagamenti GROUP BY DATE(data_creazione) ORDER BY DATE(data_creazione) DESC LIMIT 7");
    $transazioni = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $labels = [];
    $data = [];

    foreach ($transazioni as $transazione) {
        $labels[] = $transazione['data'];
        $data[] = (int)$transazione['numero_transazioni'];
    }

    // Inverti gli array per avere l'ordine cronologico corretto
    $labels = array_reverse($labels);
    $data = array_reverse($data);

    header('Content-Type: application/json');
    echo json_encode(['labels' => $labels, 'data' => $data]);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
