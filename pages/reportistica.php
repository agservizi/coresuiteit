<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
require_once '../includes/export.php';

// Controllo permessi
if (!isLoggedIn() || !hasRole('admin')) {
    header("Location: login.php");
    exit;
}

$report_type = isset($_GET['report_type']) ? sanitizeInput($_GET['report_type']) : 'clienti';
$export_format = isset($_GET['export_format']) ? sanitizeInput($_GET['export_format']) : 'html';

$report_data = [];
$report_headers = [];
$report_title = '';
$filename = 'report';

switch ($report_type) {
    case 'clienti':
        $report_data = prepareClientiExport();
        $report_headers = $report_data['headers'];
        $report_data = $report_data['data'];
        $report_title = 'Report Clienti';
        $filename = 'report_clienti';
        break;
    
    case 'fatture':
        $report_data = prepareFattureExport();
        $report_headers = $report_data['headers'];
        $report_data = $report_data['data'];
        $report_title = 'Report Fatture';
        $filename = 'report_fatture';
        break;
    
    case 'pagamenti':
        $report_data = preparePagamentiExport();
        $report_headers = $report_data['headers'];
        $report_data = $report_data['data'];
        $report_title = 'Report Pagamenti';
        $filename = 'report_pagamenti';
        break;
    
    default:
        echo "Tipo di report non valido.";
        exit;
}

if ($export_format === 'excel') {
    exportToExcel($report_data, $report_headers, $filename);
} elseif ($export_format === 'csv') {
    exportToCsv($report_data, $report_headers, $filename);
} elseif ($export_format === 'pdf') {
    exportToPdf($report_data, $report_headers, $filename, $report_title);
} else {
    // Visualizza il report in HTML
    ?>
    <div class="container mx-auto">
        <h2><?php echo $report_title; ?></h2>
        <table class="table table-zebra">
            <thead>
                <tr>
                    <?php foreach ($report_headers as $header): ?>
                        <th><?php echo $header; ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($report_data)): ?>
                    <tr>
                        <td colspan="<?php echo count($report_headers); ?>" class="text-center">Nessun dato trovato</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($report_data as $row): ?>
                        <tr>
                            <?php foreach ($row as $cell): ?>
                                <td><?php echo htmlspecialchars($cell); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div class="mt-4">
            <a href="?page=reportistica&report_type=<?php echo $report_type; ?>&export_format=excel" class="btn btn-primary">Esporta in Excel</a>
            <a href="?page=reportistica&report_type=<?php echo $report_type; ?>&export_format=csv" class="btn btn-secondary">Esporta in CSV</a>
            <a href="?page=reportistica&report_type=<?php echo $report_type; ?>&export_format=pdf" class="btn btn-info">Esporta in PDF</a>
        </div>
    </div>
    <?php
}
