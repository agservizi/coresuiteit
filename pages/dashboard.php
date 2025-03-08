<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

// Controllo accesso
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$pageTitle = "Dashboard - CoreSuite IT";

// Ottenere dati per la dashboard
$userId = $_SESSION['user_id'];

// Statistiche clienti
try {
    // ...existing code...
} catch(PDOException $e) {
    // ...existing code...
}

// Statistiche pagamenti
try {
    // ...existing code...
} catch(PDOException $e) {
    // ...existing code...
}

require_once '../includes/header.php';
?>

<div class="win11-content-container">
    <div class="app-window">
        <div class="app-title-bar">
            <div class="app-icon">
                <i class="fas fa-chart-pie"></i>
            </div>
            <h1 class="app-title">Dashboard</h1>
            <div class="win-controls">
                <button class="win-control-btn minimize" title="Minimizza">
                    <i class="fas fa-minus"></i>
                </button>
                <button class="win-control-btn maximize" title="Massimizza">
                    <i class="fas fa-square"></i>
                </button>
                <button class="win-control-btn close" title="Chiudi">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div class="app-window-content p-4">
            <div class="row mb-4">
                <div class="col-lg-6">
                    <h3>Benvenuto, <?php echo $_SESSION['nome']; ?></h3>
                    <p class="text-muted">Panoramica del tuo sistema CoreSuite IT</p>
                </div>
                <div class="col-lg-6 text-end">
                    <button class="win11-btn win11-btn-primary" data-settings-open>
                        <i class="fas fa-cog me-2"></i>Impostazioni
                    </button>
                </div>
            </div>
            
            <div class="row mb-4">
                <!-- Statistiche Card -->
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="win11-icon-container me-3 bg-primary-transparent">
                                    <i class="fas fa-users text-primary"></i>
                                </div>
                                <h5 class="card-title mb-0">Clienti</h5>
                            </div>
                            <h2 class="display-6"><?php echo $clientiTotali; ?></h2>
                            <p class="card-text text-success">
                                <i class="fas fa-arrow-up me-1"></i>
                                <?php echo $clientiNuovi; ?> nuovi questo mese
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="win11-icon-container me-3 bg-success-transparent">
                                    <i class="fas fa-euro-sign text-success"></i>
                                </div>
                                <h5 class="card-title mb-0">Entrate</h5>
                            </div>
                            <h2 class="display-6"><?php echo number_format($entrateMese, 2, ',', '.'); ?> €</h2>
                            <p class="card-text text-success">
                                <i class="fas fa-arrow-up me-1"></i>
                                <?php echo number_format($percentualeVariazione, 1); ?>% rispetto al mese scorso
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Altre cards di statistiche -->
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="win11-icon-container me-3 bg-info-transparent">
                                    <i class="fas fa-tasks text-info"></i>
                                </div>
                                <h5 class="card-title mb-0">Pratiche completate</h5>
                            </div>
                            <h2 class="display-6">42</h2>
                            <p class="card-text text-success">
                                <i class="fas fa-arrow-up me-1"></i>
                                5% rispetto a ieri
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="win11-icon-container me-3 bg-warning-transparent">
                                    <i class="fas fa-clock text-warning"></i>
                                </div>
                                <h5 class="card-title mb-0">Pratiche in attesa</h5>
                            </div>
                            <h2 class="display-6">18</h2>
                            <p class="card-text text-danger">
                                <i class="fas fa-arrow-down me-1"></i>
                                3% rispetto a ieri
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Grafici e tabelle -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title">Andamento Vendite Settimanali</h6>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="chartOptions" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="chartOptions">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i> Esporta dati</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-sync me-2"></i> Aggiorna</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Impostazioni</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="weeklyChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-4 col-lg-5">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title">Distribuzione Servizi</h6>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="pieOptions" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="pieOptions">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i> Esporta dati</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-sync me-2"></i> Aggiorna</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="categoriesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-xl-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title">Clienti recenti</h6>
                            <a href="index.php?page=clienti" class="btn btn-sm btn-primary">Vedi tutti</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Cliente</th>
                                            <th>Servizio</th>
                                            <th>Stato</th>
                                            <th>Data</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Mario Rossi</td>
                                            <td>Bollettino Postale</td>
                                            <td><span class="badge badge-success">Completato</span></td>
                                            <td>Oggi 14:30</td>
                                        </tr>
                                        <tr>
                                            <td>Giuseppe Verdi</td>
                                            <td>F24</td>
                                            <td><span class="badge badge-success">Completato</span></td>
                                            <td>Oggi 12:15</td>
                                        </tr>
                                        <tr>
                                            <td>Anna Bianchi</td>
                                            <td>Attivazione Fastweb</td>
                                            <td><span class="badge badge-warning">In elaborazione</span></td>
                                            <td>Oggi 10:22</td>
                                        </tr>
                                        <tr>
                                            <td>Lucia Neri</td>
                                            <td>Bonifico</td>
                                            <td><span class="badge badge-success">Completato</span></td>
                                            <td>Oggi 09:47</td>
                                        </tr>
                                        <tr>
                                            <td>Roberto Ferrari</td>
                                            <td>Spedizione Internazionale</td>
                                            <td><span class="badge badge-info">Spedito</span></td>
                                            <td>Ieri 16:30</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title">Attività recenti</h6>
                            <a href="#" class="btn btn-sm btn-primary">Vedi tutte</a>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <!-- ...existing code... -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
