<?php
// Controllo permessi
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Gestione form di inserimento nuovo pagamento
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $cliente_id = sanitizeInput($_POST['cliente_id']);
    $tipo_pagamento = sanitizeInput($_POST['tipo_pagamento']);
    $importo = sanitizeInput($_POST['importo']);
    $descrizione = sanitizeInput($_POST['descrizione']);
    $stato = sanitizeInput($_POST['stato']);
    
    try {
        $transaction_id = generateTransactionId();
        $stmt = $conn->prepare("INSERT INTO pagamenti (transaction_id, cliente_id, tipo, importo, descrizione, stato, data_creazione) 
                              VALUES (:transaction_id, :cliente_id, :tipo, :importo, :descrizione, :stato, NOW())");
        $stmt->bindParam(':transaction_id', $transaction_id);
        $stmt->bindParam(':cliente_id', $cliente_id);
        $stmt->bindParam(':tipo', $tipo_pagamento);
        $stmt->bindParam(':importo', $importo);
        $stmt->bindParam(':descrizione', $descrizione);
        $stmt->bindParam(':stato', $stato);
        
        if ($stmt->execute()) {
            $success_message = "Pagamento registrato con successo!";
        } else {
            $error_message = "Errore durante la registrazione del pagamento.";
        }
    } catch(PDOException $e) {
        $error_message = "Errore database: " . $e->getMessage();
    }
}

// Query per ottenere i pagamenti
try {
    $stmt = $conn->query("SELECT p.*, c.nome, c.cognome FROM pagamenti p 
                         LEFT JOIN clienti c ON p.cliente_id = c.id 
                         ORDER BY p.data_creazione DESC 
                         LIMIT 100");
    $pagamenti = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error_message = "Errore caricamento pagamenti: " . $e->getMessage();
    $pagamenti = [];
}

// Query per ottenere i clienti per il form
try {
    $stmt = $conn->query("SELECT id, nome, cognome FROM clienti ORDER BY cognome, nome");
    $clienti = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $clienti = [];
}
?>

<div class="container-fluid">
    <!-- Statistiche principali -->
    <div class="row stats-container">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-stat bg-primary-gradient">
                <div class="stat-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-label">Pagamenti Oggi</div>
                <div class="stat-value">€ 1.250,00</div>
                <div class="stat-change">
                    <i class="fas fa-arrow-up"></i> 15% rispetto a ieri
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-stat bg-success-gradient">
                <div class="stat-icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div class="stat-label">Pagamenti Questo Mese</div>
                <div class="stat-value">€ 28.630,00</div>
                <div class="stat-change">
                    <i class="fas fa-arrow-up"></i> 8% rispetto al mese scorso
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-stat bg-info-gradient">
                <div class="stat-icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="stat-label">Ricevute Emesse</div>
                <div class="stat-value">145</div>
                <div class="stat-change">
                    <i class="fas fa-arrow-up"></i> 12% rispetto al mese scorso
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-stat bg-warning-gradient">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="stat-label">Pagamenti in Attesa</div>
                <div class="stat-value">12</div>
                <div class="stat-change">
                    <i class="fas fa-arrow-down"></i> 5% rispetto a ieri
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filtri e nuovo pagamento -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Ricerca pagamenti</h6>
                </div>
                <div class="card-body">
                    <form action="" method="GET" class="row g-3">
                        <input type="hidden" name="page" value="pagamenti">
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="dataInizio" class="form-label">Data inizio</label>
                                <input type="date" class="form-control" id="dataInizio" name="dataInizio">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="dataFine" class="form-label">Data fine</label>
                                <input type="date" class="form-control" id="dataFine" name="dataFine">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="stato" class="form-label">Stato</label>
                                <select class="form-select" id="stato" name="stato">
                                    <option value="">Tutti</option>
                                    <option value="completato">Completato</option>
                                    <option value="pending">In attesa</option>
                                    <option value="fallito">Fallito</option>
                                    <option value="rimborsato">Rimborsato</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="metodo" class="form-label">Metodo di pagamento</label>
                                <select class="form-select" id="metodo" name="metodo">
                                    <option value="">Tutti</option>
                                    <option value="contanti">Contanti</option>
                                    <option value="carta">Carta di credito/debito</option>
                                    <option value="bonifico">Bonifico bancario</option>
                                    <option value="paypal">PayPal</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cliente" class="form-label">Cliente</label>
                                <input type="text" class="form-control" id="cliente" name="cliente" placeholder="Nome, Cognome o CF">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="servizio" class="form-label">Servizio</label>
                                <select class="form-select" id="servizio" name="servizio">
                                    <option value="">Tutti</option>
                                    <option value="telefonia">Telefonia</option>
                                    <option value="energia">Energia</option>
                                    <option value="spedizioni">Spedizioni</option>
                                    <option value="servizi-digitali">Servizi Digitali</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Cerca
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-undo me-2"></i>Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="card-title">Registra nuovo pagamento</h6>
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="text-muted mb-4">Registra un nuovo pagamento o ricevuta nel sistema.</p>
                    
                    <div class="mt-auto">
                        <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#nuovoPagamentoModal">
                            <i class="fas fa-plus-circle me-2"></i>Nuovo pagamento
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabella pagamenti -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="card-title">Pagamenti recenti</h6>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="exportOptions" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-download me-1"></i> Esporta
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="exportOptions">
                    <li><a class="dropdown-item" href="#"><i class="far fa-file-excel me-2"></i>Excel</a></li>
                    <li><a class="dropdown-item" href="#"><i class="far fa-file-pdf me-2"></i>PDF</a></li>
                    <li><a class="dropdown-item" href="#"><i class="far fa-file-csv me-2"></i>CSV</a></li>
                </ul>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data</th>
                            <th>Cliente</th>
                            <th>Servizio</th>
                            <th>Importo</th>
                            <th>Metodo</th>
                            <th>Stato</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#PAG-2023-001</td>
                            <td>15/05/2023</td>
                            <td>Mario Rossi</td>
                            <td>Telefonia</td>
                            <td>€ 199,90</td>
                            <td>Carta di credito</td>
                            <td><span class="badge badge-soft-success">Completato</span></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="#" class="btn btn-outline-info" data-bs-toggle="tooltip" title="Dettagli"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Ricevuta"><i class="fas fa-file-invoice"></i></a>
                                    <a href="#" class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Annulla"><i class="fas fa-times"></i></a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>#PAG-2023-002</td>
                            <td>14/05/2023</td>
                            <td>Giulia Verdi</td>
                            <td>Energia</td>
                            <td>€ 125,50</td>
                            <td>Bonifico bancario</td>
                            <td><span class="badge badge-soft-warning">In attesa</span></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="#" class="btn btn-outline-info" data-bs-toggle="tooltip" title="Dettagli"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="btn btn-outline-success" data-bs-toggle="tooltip" title="Conferma"><i class="fas fa-check"></i></a>
                                    <a href="#" class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Annulla"><i class="fas fa-times"></i></a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>#PAG-2023-003</td>
                            <td>13/05/2023</td>
                            <td>Luca Bianchi</td>
                            <td>Servizi Digitali</td>
                            <td>€ 79,99</td>
                            <td>PayPal</td>
                            <td><span class="badge badge-soft-success">Completato</span></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="#" class="btn btn-outline-info" data-bs-toggle="tooltip" title="Dettagli"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Ricevuta"><i class="fas fa-file-invoice"></i></a>
                                    <a href="#" class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Annulla"><i class="fas fa-times"></i></a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>#PAG-2023-004</td>
                            <td>12/05/2023</td>
                            <td>Anna Neri</td>
                            <td>Spedizioni</td>
                            <td>€ 15,90</td>
                            <td>Contanti</td>
                            <td><span class="badge badge-soft-success">Completato</span></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="#" class="btn btn-outline-info" data-bs-toggle="tooltip" title="Dettagli"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Ricevuta"><i class="fas fa-file-invoice"></i></a>
                                    <a href="#" class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Annulla"><i class="fas fa-times"></i></a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>#PAG-2023-005</td>
                            <td>10/05/2023</td>
                            <td>Paolo Gialli</td>
                            <td>Telefonia</td>
                            <td>€ 299,00</td>
                            <td>Carta di credito</td>
                            <td><span class="badge badge-soft-danger">Fallito</span></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="#" class="btn btn-outline-info" data-bs-toggle="tooltip" title="Dettagli"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="btn btn-outline-warning" data-bs-toggle="tooltip" title="Riprova"><i class="fas fa-redo"></i></a>
                                    <a href="#" class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Annulla"><i class="fas fa-times"></i></a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <nav aria-label="Paginazione">
                <ul class="pagination justify-content-center mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#"><i class="fas fa-chevron-left"></i></a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Modal per nuovo pagamento -->
<div class="modal fade" id="nuovoPagamentoModal" tabindex="-1" aria-labelledby="nuovoPagamentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuovoPagamentoModalLabel">Registra nuovo pagamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="nuovoPagamentoForm" class="row g-3">
                    <div class="col-md-6">
                        <label for="clienteModal" class="form-label form-required">Cliente</label>
                        <select class="form-select" id="clienteModal" name="clienteModal" required>
                            <option value="" selected disabled>Seleziona cliente</option>
                            <option value="1">Mario Rossi</option>
                            <option value="2">Giulia Verdi</option>
                            <option value="3">Luca Bianchi</option>
                            <option value="4">Anna Neri</option>
                            <option value="5">Paolo Gialli</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="servizioModal" class="form-label form-required">Servizio</label>
                        <select class="form-select" id="servizioModal" name="servizioModal" required>
                            <option value="" selected disabled>Seleziona servizio</option>
                            <option value="telefonia">Telefonia</option>
                            <option value="energia">Energia</option>
                            <option value="spedizioni">Spedizioni</option>
                            <option value="servizi-digitali">Servizi Digitali</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="importo" class="form-label form-required">Importo (€)</label>
                        <input type="number" step="0.01" min="0.01" class="form-control" id="importo" name="importo" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="metodoPagamento" class="form-label form-required">Metodo di pagamento</label>
                        <select class="form-select" id="metodoPagamento" name="metodoPagamento" required>
                            <option value="contanti">Contanti</option>
                            <option value="carta">Carta di credito/debito</option>
                            <option value="bonifico">Bonifico bancario</option>
                            <option value="paypal">PayPal</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="dataPagamento" class="form-label form-required">Data pagamento</label>
                        <input type="date" class="form-control" id="dataPagamento" name="dataPagamento" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="numeroRicevuta" class="form-label">Numero ricevuta</label>
                        <input type="text" class="form-control" id="numeroRicevuta" name="numeroRicevuta" placeholder="Generato automaticamente">
                    </div>
                    
                    <div class="col-12">
                        <label for="descrizione" class="form-label">Descrizione</label>
                        <textarea class="form-control" id="descrizione" name="descrizione" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="submit" form="nuovoPagamentoForm" class="btn btn-primary">Registra pagamento</button>
            </div>
        </div>
    </div>
</div>
