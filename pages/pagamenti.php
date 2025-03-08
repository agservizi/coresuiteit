<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

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
            
            // Invia email di notifica
            $cliente_email = getClientEmailById($cliente_id);
            $subject = "Nuovo Pagamento Registrato";
            $message = "Caro cliente, il tuo pagamento di €{$importo} per {$tipo_pagamento} è stato registrato con successo.";
            sendNotificationEmail($cliente_email, $subject, $message);
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
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?php echo $success_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
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
                        <?php if (empty($pagamenti)): ?>
                            <tr>
                                <td colspan="8" class="text-center">Nessun pagamento trovato</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pagamenti as $pagamento): ?>
                                <tr>
                                    <td><?php echo $pagamento['transaction_id']; ?></td>
                                    <td><?php echo formatDate($pagamento['data_creazione']); ?></td>
                                    <td><?php echo $pagamento['cognome'] . ' ' . $pagamento['nome']; ?></td>
                                    <td><?php echo $pagamento['tipo']; ?></td>
                                    <td>€<?php echo number_format($pagamento['importo'], 2, ',', '.'); ?></td>
                                    <td><?php echo $pagamento['metodo']; ?></td>
                                    <td>
                                        <?php 
                                        $badge_class = '';
                                        switch($pagamento['stato']) {
                                            case 'Completato': $badge_class = 'badge-soft-success'; break;
                                            case 'In elaborazione': $badge_class = 'badge-soft-warning'; break;
                                            case 'Annullato': $badge_class = 'badge-soft-danger'; break;
                                            default: $badge_class = 'badge-soft-info';
                                        }
                                        ?>
                                        <span class="badge <?php echo $badge_class; ?>"><?php echo $pagamento['stato']; ?></span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="#" class="btn btn-outline-info" data-bs-toggle="tooltip" title="Dettagli"><i class="fas fa-eye"></i></a>
                                            <a href="#" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Ricevuta"><i class="fas fa-file-invoice"></i></a>
                                            <a href="#" class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Annulla" onclick="return confirmDelete(<?php echo $pagamento['id']; ?>, 'pagamento')"><i class="fas fa-times"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
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
                <form id="nuovoPagamentoForm" method="post" action="" class="row g-3">
                    <input type="hidden" name="action" value="add">
                    <div class="col-md-6">
                        <label for="cliente_id" class="form-label form-required">Cliente</label>
                        <select class="form-select" id="cliente_id" name="cliente_id" required>
                            <option value="" selected disabled>Seleziona cliente</option>
                            <?php foreach ($clienti as $cliente): ?>
                                <option value="<?php echo $cliente['id']; ?>">
                                    <?php echo $cliente['cognome'] . ' ' . $cliente['nome']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="tipo_pagamento" class="form-label form-required">Servizio</label>
                        <select class="form-select" id="tipo_pagamento" name="tipo_pagamento" required>
                            <option value="" selected disabled>Seleziona servizio</option>
                            <option value="Telefonia">Telefonia</option>
                            <option value="Energia">Energia</option>
                            <option value="Bollettino">Bollettino Postale</option>
                            <option value="F24">F24</option>
                            <option value="PagoPA">PagoPA</option>
                            <option value="Bonifico">Bonifico Bancario</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="importo" class="form-label form-required">Importo (€)</label>
                        <input type="number" step="0.01" min="0.01" class="form-control" id="importo" name="importo" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="stato" class="form-label">Stato</label>
                        <select class="form-select" id="stato" name="stato">
                            <option value="Completato">Completato</option>
                            <option value="In elaborazione">In elaborazione</option>
                            <option value="Annullato">Annullato</option>
                        </select>
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
