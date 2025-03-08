<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="row stats-container">
        <!-- Statistiche principali -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-stat bg-primary-gradient">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-label">Vendite oggi</div>
                <div class="stat-value">€ 2.145</div>
                <div class="stat-change">
                    <i class="fas fa-arrow-up"></i> 12% rispetto a ieri
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-stat bg-success-gradient">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-label">Nuovi clienti</div>
                <div class="stat-value">24</div>
                <div class="stat-change">
                    <i class="fas fa-arrow-up"></i> 8% rispetto a ieri
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-stat bg-info-gradient">
                <div class="stat-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="stat-label">Pratiche completate</div>
                <div class="stat-value">42</div>
                <div class="stat-change">
                    <i class="fas fa-arrow-up"></i> 5% rispetto a ieri
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-stat bg-warning-gradient">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-label">Pratiche in attesa</div>
                <div class="stat-value">18</div>
                <div class="stat-change">
                    <i class="fas fa-arrow-down"></i> 3% rispetto a ieri
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts -->
    <div class="row">
        <!-- Grafico vendite settimanali -->
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
        
        <!-- Distribuzione categorie -->
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
    
    <!-- Recent Transactions and Activity Timeline -->
    <div class="row">
        <!-- Ultimi clienti -->
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
        
        <!-- Timeline attività -->
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
