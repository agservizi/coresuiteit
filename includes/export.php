<?php
/**
 * Funzioni per l'esportazione dei dati in vari formati
 */

require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use TCPDF;

/**
 * Esporta dati in formato Excel (XLSX)
 * @param array $data Array di dati da esportare
 * @param array $headers Array di intestazioni di colonna
 * @param string $filename Nome del file da generare
 * @param string $sheetName Nome del foglio excel
 * @param bool $download Se true, forza il download del file
 * @return string|bool Percorso del file generato o false in caso di errore
 */
function exportToExcel($data, $headers, $filename, $sheetName = 'Sheet1', $download = true) {
    try {
        // Crea un nuovo oggetto Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($sheetName);
        
        // Imposta le intestazioni
        $col = 1;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($col++, 1, $header);
        }
        
        // Imposta lo stile delle intestazioni
        $headerRange = 'A1:' . chr(64 + count($headers)) . '1';
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFCCCCCC');
        
        $sheet->getStyle($headerRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Aggiungi i dati
        $row = 2;
        foreach ($data as $rowData) {
            $col = 1;
            foreach ($rowData as $value) {
                $sheet->setCellValueByColumnAndRow($col++, $row, $value);
            }
            $row++;
        }
        
        // Auto-dimensiona le colonne
        foreach (range('A', chr(64 + count($headers))) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        
        // Crea il writer Excel
        $writer = new Xlsx($spreadsheet);
        
        if ($download) {
            // Imposta gli header per il download
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            header('Cache-Control: max-age=0');
            
            // Salva direttamente allo stream di output
            $writer->save('php://output');
            exit;
        } else {
            // Salva su file
            $filePath = '../exports/' . $filename . '.xlsx';
            $writer->save($filePath);
            return $filePath;
        }
    } catch (Exception $e) {
        error_log("Errore esportazione Excel: " . $e->getMessage());
        return false;
    }
}

/**
 * Esporta dati in formato CSV
 * @param array $data Array di dati da esportare
 * @param array $headers Array di intestazioni di colonna
 * @param string $filename Nome del file da generare
 * @param bool $download Se true, forza il download del file
 * @param string $delimiter Carattere separatore
 * @return string|bool Percorso del file generato o false in caso di errore
 */
function exportToCsv($data, $headers, $filename, $download = true, $delimiter = ',') {
    try {
        // Crea un nuovo oggetto Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Imposta le intestazioni
        $col = 1;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($col++, 1, $header);
        }
        
        // Aggiungi i dati
        $row = 2;
        foreach ($data as $rowData) {
            $col = 1;
            foreach ($rowData as $value) {
                $sheet->setCellValueByColumnAndRow($col++, $row, $value);
            }
            $row++;
        }
        
        // Crea il writer CSV
        $writer = new Csv($spreadsheet);
        $writer->setDelimiter($delimiter);
        $writer->setEnclosure('"');
        $writer->setLineEnding("\r\n");
        $writer->setUseBOM(true);
        
        if ($download) {
            // Imposta gli header per il download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment;filename="' . $filename . '.csv"');
            header('Cache-Control: max-age=0');
            
            // Salva direttamente allo stream di output
            $writer->save('php://output');
            exit;
        } else {
            // Salva su file
            $filePath = '../exports/' . $filename . '.csv';
            $writer->save($filePath);
            return $filePath;
        }
    } catch (Exception $e) {
        error_log("Errore esportazione CSV: " . $e->getMessage());
        return false;
    }
}

/**
 * Esporta dati in formato PDF
 * @param array $data Array di dati da esportare
 * @param array $headers Array di intestazioni di colonna
 * @param string $filename Nome del file da generare
 * @param string $title Titolo del documento
 * @param bool $download Se true, forza il download del file
 * @return string|bool Percorso del file generato o false in caso di errore
 */
function exportToPdf($data, $headers, $filename, $title = '', $download = true) {
    try {
        // Crea un nuovo oggetto PDF
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8');
        
        // Imposta le informazioni del documento
        $pdf->SetCreator('CoreSuite');
        $pdf->SetAuthor('CoreSuite');
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        
        // Rimuovi intestazione e piè di pagina
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Aggiungi una pagina
        $pdf->AddPage();
        
        // Imposta il font
        $pdf->SetFont('helvetica', '', 10);
        
        // Titolo
        if (!empty($title)) {
            $pdf->SetFont('helvetica', 'B', 16);
            $pdf->Cell(0, 10, $title, 0, 1, 'C');
            $pdf->Ln(5);
            $pdf->SetFont('helvetica', '', 10);
        }
        
        // Calcola la larghezza di ogni colonna
        $width = 275 / count($headers);
        
        // Intestazione tabella
        $pdf->SetFillColor(230, 230, 230);
        $pdf->SetFont('helvetica', 'B', 10);
        
        foreach ($headers as $header) {
            $pdf->Cell($width, 10, $header, 1, 0, 'C', 1);
        }
        $pdf->Ln();
        
        // Dati
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetFillColor(255, 255, 255);
        
        foreach ($data as $row) {
            foreach ($row as $cell) {
                $pdf->Cell($width, 8, $cell, 1, 0, 'L');
            }
            $pdf->Ln();
        }
        
        if ($download) {
            // Output PDF per il download
            $pdf->Output($filename . '.pdf', 'D');
            exit;
        } else {
            // Salva su file
            $filePath = '../exports/' . $filename . '.pdf';
            $pdf->Output($filePath, 'F');
            return $filePath;
        }
    } catch (Exception $e) {
        error_log("Errore esportazione PDF: " . $e->getMessage());
        return false;
    }
}

/**
 * Prepara i dati dei clienti per l'esportazione
 * @return array Array con headers e data
 */
function prepareClientiExport() {
    global $conn;
    
    try {
        $stmt = $conn->query("SELECT id, nome, cognome, codice_fiscale, email, telefono, indirizzo, citta, cap, data_registrazione FROM clienti");
        $clienti = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Definisci le intestazioni
        $headers = [
            'ID', 'Nome', 'Cognome', 'Codice Fiscale', 'Email', 'Telefono', 
            'Indirizzo', 'Città', 'CAP', 'Data Registrazione'
        ];
        
        return [
            'headers' => $headers,
            'data' => $clienti
        ];
    } catch (PDOException $e) {
        error_log("Errore preparazione export clienti: " . $e->getMessage());
        return [
            'headers' => [],
            'data' => []
        ];
    }
}

/**
 * Prepara i dati delle fatture per l'esportazione
 * @return array Array con headers e data
 */
function prepareFattureExport() {
    global $conn;
    
    try {
        $stmt = $conn->query("SELECT f.id, f.fattura_id, c.nome, c.cognome, f.importo, f.descrizione, f.stato, f.data_creazione 
                             FROM fatture f 
                             LEFT JOIN clienti c ON f.cliente_id = c.id");
        $fatture = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Definisci le intestazioni
        $headers = [
            'ID', 'Numero Fattura', 'Nome Cliente', 'Cognome Cliente', 
            'Importo', 'Descrizione', 'Stato', 'Data Creazione'
        ];
        
        return [
            'headers' => $headers,
            'data' => $fatture
        ];
    } catch (PDOException $e) {
        error_log("Errore preparazione export fatture: " . $e->getMessage());
        return [
            'headers' => [],
            'data' => []
        ];
    }
}

/**
 * Prepara i dati dei pagamenti per l'esportazione
 * @return array Array con headers e data
 */
function preparePagamentiExport() {
    global $conn;
    
    try {
        $stmt = $conn->query("SELECT p.id, p.transaction_id, c.nome, c.cognome, p.tipo, p.importo, p.descrizione, p.stato, p.data_creazione 
                             FROM pagamenti p 
                             LEFT JOIN clienti c ON p.cliente_id = c.id");
        $pagamenti = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Definisci le intestazioni
        $headers = [
            'ID', 'ID Transazione', 'Nome Cliente', 'Cognome Cliente', 
            'Tipo', 'Importo', 'Descrizione', 'Stato', 'Data'
        ];
        
        return [
            'headers' => $headers,
            'data' => $pagamenti
        ];
    } catch (PDOException $e) {
        error_log("Errore preparazione export pagamenti: " . $e->getMessage());
        return [
            'headers' => [],
            'data' => []
        ];
    }
}
?>
