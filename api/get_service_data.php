<?php
require_once '../config/config.php';

try {
    $stmt = $conn->query("SELECT tipo, COUNT(*) AS numero_servizi FROM pagamenti GROUP BY tipo");
    $servizi = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $labels = [];
    $data = [];

    foreach ($servizi as $servizio) {
        $labels[] = $servizio['tipo'];
        $data[] = (int)$servizio['numero_servizi'];
    }

    header('Content-Type: application/json');
    echo json_encode(['labels' => $labels, 'data' => $data]);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
