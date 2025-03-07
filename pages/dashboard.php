<div class="container-fluid">
    <div class="row">
        <!-- Statistiche principali -->
        <div class="col-md-3">
            <div class="card dashboard-stat bg-info-light">
                <div class="card-body">
                    <h5 class="card-title text-primary">Transazioni oggi</h5>
                    <h2 class="display-4 font-weight-bold">24</h2>
                    <p class="card-text"><i class="fas fa-arrow-up text-success"></i> +12% rispetto a ieri</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-stat bg-success-light">
                <div class="card-body">
                    <h5 class="card-title text-success">Incasso giornaliero</h5>
                    <h2 class="display-4 font-weight-bold">€1.254</h2>
                    <p class="card-text"><i class="fas fa-arrow-up text-success"></i> +5% rispetto a ieri</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-stat bg-warning-light">
                <div class="card-body">
                    <h5 class="card-title text-warning">Nuovi clienti</h5>
                    <h2 class="display-4 font-weight-bold">7</h2>
                    <p class="card-text"><i class="fas fa-arrow-up text-success"></i> +2 rispetto a ieri</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-stat bg-danger-light">
                <div class="card-body">
                    <h5 class="card-title text-danger">Pratiche in attesa</h5>
                    <h2 class="display-4 font-weight-bold">12</h2>
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
                                <tr>
                                    <td>TR2023080001</td>
                                    <td>Mario Rossi</td>
                                    <td>Bollettino Postale</td>
                                    <td>€78,50</td>
                                    <td>Oggi 14:30</td>
                                    <td><span class="badge bg-success">Completato</span></td>
                                </tr>
                                <tr>
                                    <td>TR2023080002</td>
                                    <td>Giuseppe Verdi</td>
                                    <td>F24</td>
                                    <td>€256,00</td>
                                    <td>Oggi 12:15</td>
                                    <td><span class="badge bg-success">Completato</span></td>
                                </tr>
                                <tr>
                                    <td>TR2023080003</td>
                                    <td>Anna Bianchi</td>
                                    <td>Attivazione Fastweb</td>
                                    <td>€35,00</td>
                                    <td>Oggi 10:22</td>
                                    <td><span class="badge bg-warning">In elaborazione</span></td>
                                </tr>
                                <tr>
                                    <td>TR2023080004</td>
                                    <td>Lucia Neri</td>
                                    <td>Bonifico</td>
                                    <td>€320,00</td>
                                    <td>Oggi 09:47</td>
                                    <td><span class="badge bg-success">Completato</span></td>
                                </tr>
                                <tr>
                                    <td>TR2023080005</td>
                                    <td>Roberto Ferrari</td>
                                    <td>Spedizione Internazionale</td>
                                    <td>€45,70</td>
                                    <td>Ieri 16:30</td>
                                    <td><span class="badge bg-info">Spedito</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
