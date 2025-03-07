<div class="container mx-auto">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="card bg-primary text-primary-content">
            <div class="card-body">
                <h2 class="card-title text-3xl">24</h2>
                <p>Transazioni oggi</p>
                <div class="text-sm">
                    <i class="fas fa-arrow-up"></i> +12% rispetto a ieri
                </div>
            </div>
        </div>
        <div class="card bg-accent text-accent-content">
            <div class="card-body">
                <h2 class="card-title text-3xl">€1.254</h2>
                <p>Incasso giornaliero</p>
                <div class="text-sm">
                    <i class="fas fa-arrow-up"></i> +5% rispetto a ieri
                </div>
            </div>
        </div>
        <div class="card bg-secondary text-secondary-content">
            <div class="card-body">
                <h2 class="card-title text-3xl">7</h2>
                <p>Nuovi clienti</p>
                <div class="text-sm">
                    <i class="fas fa-arrow-up"></i> +2 rispetto a ieri
                </div>
            </div>
        </div>
        <div class="card bg-info text-info-content">
            <div class="card-body">
                <h2 class="card-title text-3xl">12</h2>
                <p>Pratiche in attesa</p>
                <div class="text-sm">
                    <i class="fas fa-arrow-down"></i> -3 rispetto a ieri
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="card bg-base-100 shadow-xl lg:col-span-2">
            <div class="card-body">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="card-title">Andamento transazioni</h2>
                    <div class="join">
                        <button class="join-item btn btn-sm">Settimana</button>
                        <button class="join-item btn btn-sm btn-active">Mese</button>
                        <button class="join-item btn btn-sm">Anno</button>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="transazioniChart"></canvas>
                </div>
            </div>
        </div>
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title mb-4">Distribuzione servizi</h2>
                <div class="h-64">
                    <canvas id="serviziChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions Table -->
    <div class="card bg-base-100 shadow-xl mb-8">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title">Ultime transazioni</h2>
                <a href="index.php?page=pagamenti" class="btn btn-primary btn-sm">Vedi tutte</a>
            </div>
            <div class="overflow-x-auto">
                <table class="table table-zebra">
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
                            <td><span class="badge badge-success">Completato</span></td>
                        </tr>
                        <tr>
                            <td>TR2023080002</td>
                            <td>Giuseppe Verdi</td>
                            <td>F24</td>
                            <td>€256,00</td>
                            <td>Oggi 12:15</td>
                            <td><span class="badge badge-success">Completato</span></td>
                        </tr>
                        <tr>
                            <td>TR2023080003</td>
                            <td>Anna Bianchi</td>
                            <td>Attivazione Fastweb</td>
                            <td>€35,00</td>
                            <td>Oggi 10:22</td>
                            <td><span class="badge badge-warning">In elaborazione</span></td>
                        </tr>
                        <tr>
                            <td>TR2023080004</td>
                            <td>Lucia Neri</td>
                            <td>Bonifico</td>
                            <td>€320,00</td>
                            <td>Oggi 09:47</td>
                            <td><span class="badge badge-success">Completato</span></td>
                        </tr>
                        <tr>
                            <td>TR2023080005</td>
                            <td>Roberto Ferrari</td>
                            <td>Spedizione Internazionale</td>
                            <td>€45,70</td>
                            <td>Ieri 16:30</td>
                            <td><span class="badge badge-info">Spedito</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
