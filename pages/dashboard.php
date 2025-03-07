<div class="container-fluid">
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
                                    <td>Telefonia</td>
                                    <td><span class="badge badge-soft-success">Completato</span></td>
                                    <td>12/05/2023</td>
                                </tr>
                                <tr>
                                    <td>Giulia Verdi</td>
                                    <td>Energia</td>
                                    <td><span class="badge badge-soft-warning">In attesa</span></td>
                                    <td>10/05/2023</td>
                                </tr>
                                <tr>
                                    <td>Luca Bianchi</td>
                                    <td>Pagamenti</td>
                                    <td><span class="badge badge-soft-success">Completato</span></td>
                                    <td>09/05/2023</td>
                                </tr>
                                <tr>
                                    <td>Anna Neri</td>
                                    <td>Servizi Digitali</td>
                                    <td><span class="badge badge-soft-danger">Scaduto</span></td>
                                    <td>08/05/2023</td>
                                </tr>
                                <tr>
                                    <td>Paolo Gialli</td>
                                    <td>Spedizioni</td>
                                    <td><span class="badge badge-soft-info">In lavorazione</span></td>
                                    <td>07/05/2023</td>
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
                        <div class="timeline-item">
                            <div class="timeline-item-header">
                                <div class="timeline-title">Contratto firmato</div>
                                <div class="timeline-time">15 minuti fa</div>
                            </div>
                            <p class="mb-0 text-muted">Un nuovo contratto è stato firmato dal cliente Mario Rossi per il servizio di telefonia mobile.</p>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-item-header">
                                <div class="timeline-title">Pagamento ricevuto</div>
                                <div class="timeline-time">2 ore fa</div>
                            </div>
                            <p class="mb-0 text-muted">È stato registrato un pagamento di €450 da parte del cliente Luca Bianchi.</p>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-item-header">
                                <div class="timeline-title">Nuovo cliente registrato</div>
                                <div class="timeline-time">4 ore fa</div>
                            </div>
                            <p class="mb-0 text-muted">Anna Neri è stata registrata come nuovo cliente per servizi digitali.</p>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-item-header">
                                <div class="timeline-title">Contratto luce scaduto</div>
                                <div class="timeline-time">Ieri</div>
                            </div>
                            <p class="mb-0 text-muted">Il contratto di fornitura energia di Paolo Gialli è scaduto e deve essere rinnovato.</p>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-item-header">
                                <div class="timeline-title">Nuova spedizione</div>
                                <div class="timeline-time">2 giorni fa</div>
                            </div>
                            <p class="mb-0 text-muted">Una nuova spedizione è stata registrata per il cliente Giulia Verdi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Area notifiche toast -->
    <div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3"></div>
    
    <script>
        // Mostra un esempio di notifica al caricamento (solo per demo)
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                showNotification('Notifica di sistema', 'Benvenuto nel dashboard CoreSuite IT. Ci sono 3 attività in attesa di approvazione.', 'info');
            }, 2000);
        });
    </script>
</div>
