<?php
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
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
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['error' => 'Metodo non consentito']);
}
?>
