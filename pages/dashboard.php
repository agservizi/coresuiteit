<?php
require_once BASE_PATH . 'includes/functions.php';

// Controllo permessi
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Query per ottenere le statistiche principali
try {
    $stmt = $conn->query("SELECT COUNT(*) AS transazioni_oggi FROM pagamenti WHERE DATE(data_creazione) = CURDATE()");
    $transazioni_oggi = $stmt->fetch(PDO::FETCH_ASSOC)['transazioni_oggi'];

    $stmt = $conn->query("SELECT SUM(importo) AS incasso_giornaliero FROM pagamenti WHERE DATE(data_creazione) = CURDATE()");
    $incasso_giornaliero = $stmt->fetch(PDO::FETCH_ASSOC)['incasso_giornaliero'];
    $incasso_giornaliero = $incasso_giornaliero !== null ? $incasso_giornaliero : 0;

    $stmt = $conn->query("SELECT COUNT(*) AS nuovi_clienti FROM clienti WHERE DATE(data_registrazione) = CURDATE()");
    $nuovi_clienti = $stmt->fetch(PDO::FETCH_ASSOC)['nuovi_clienti'];

    $stmt = $conn->query("SELECT COUNT(*) AS pratiche_in_attesa FROM telefonia WHERE stato = 'In attivazione'");
    $pratiche_in_attesa = $stmt->fetch(PDO::FETCH_ASSOC)['pratiche_in_attesa'];
} catch(PDOException $e) {
    $transazioni_oggi = 0;
    $incasso_giornaliero = 0;
    $nuovi_clienti = 0;
    $pratiche_in_attesa = 0;
}

// Query per ottenere le ultime transazioni
try {
    $stmt = $conn->query("SELECT p.transaction_id, c.nome, c.cognome, p.tipo, p.importo, p.data_creazione, p.stato 
                          FROM pagamenti p 
                          LEFT JOIN clienti c ON p.cliente_id = c.id 
                          ORDER BY p.data_creazione DESC 
                          LIMIT 5");
    $ultime_transazioni = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $ultime_transazioni = [];
}
?>

<div class="container-fluid">
    <div class="row">
        <!-- Statistiche principali -->
        <div class="col-md-3">
            <div class="card dashboard-stat bg-info-light">
                <div class="card-body">
                    <h5 class="card-title text-primary">Transazioni oggi</h5>
                    <h2 class="display-4 font-weight-bold"><?php echo $transazioni_oggi; ?></h2>
                    <p class="card-text"><i class="fas fa-arrow-up text-success"></i> +12% rispetto a ieri</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-stat bg-success-light">
                <div class="card-body">
                    <h5 class="card-title text-success">Incasso giornaliero</h5>
                    <h2 class="display-4 font-weight-bold">€<?php echo number_format($incasso_giornaliero, 2, ',', '.'); ?></h2>
                    <p class="card-text"><i class="fas fa-arrow-up text-success"></i> +5% rispetto a ieri</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-stat bg-warning-light">
                <div class="card-body">
                    <h5 class="card-title text-warning">Nuovi clienti</h5>
                    <h2 class="display-4 font-weight-bold"><?php echo $nuovi_clienti; ?></h2>
                    <p class="card-text"><i class="fas fa-arrow-up text-success"></i> +2 rispetto a ieri</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-stat bg-danger-light">
                <div class="card-body">
                    <h5 class="card-title text-danger">Pratiche in attesa</h5>
                    <h2 class="display-4 font-weight-bold"><?php echo $pratiche_in_attesa; ?></h2>
                    <p class="card-text"><i class="fas fa-arrow-down text-danger"></i> -3 rispetto a ieri</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Grafici -->
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Andamento transazioni</h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-secondary">Settimana</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary">Mese</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary">Anno</button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="transazioniChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Distribuzione servizi</h5>
                </div>
                <div class="card-body">
                    <canvas id="serviziChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Ultime transazioni -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Ultime transazioni</h5>
                    <a href="index.php?page=pagamenti" class="btn btn-sm btn-primary">Vedi tutte</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Servizio</th>
                                    <th>Importo</th>
                                    <th>Data</th>
                                    <th>Stato</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($ultime_transazioni)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Nessuna transazione trovata</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($ultime_transazioni as $transazione): ?>
                                        <tr>
                                            <td><?php echo $transazione['transaction_id']; ?></td>
                                            <td><?php echo $transazione['cognome'] . ' ' . $transazione['nome']; ?></td>
                                            <td><?php echo $transazione['tipo']; ?></td>
                                            <td>€<?php echo number_format($transazione['importo'], 2, ',', '.'); ?></td>
                                            <td><?php echo formatDate($transazione['data_creazione']); ?></td>
                                            <td>
                                                <?php 
                                                $badge_class = '';
                                                switch($transazione['stato']) {
                                                    case 'Completato': $badge_class = 'bg-success'; break;
                                                    case 'In elaborazione': $badge_class = 'bg-warning'; break;
                                                    case 'Annullato': $badge_class = 'bg-danger'; break;
                                                    default: $badge_class = 'bg-secondary';
                                                }
                                                ?>
                                                <span class="badge <?php echo $badge_class; ?>"><?php echo $transazione['stato']; ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
