</div> <!-- End page content -->
        </div>
        
        <!-- Sidebar -->
        <div class="drawer-side">
            <label for="drawer-toggle" aria-label="close sidebar" class="drawer-overlay"></label>
            <div class="bg-base-200 w-64 h-full">
                <div class="p-4 text-center">
                    <img src="assets/img/logo.png" alt="Agenzia Servizi" class="mx-auto w-24 h-24">
                    <h2 class="text-xl font-bold mt-2">Agenzia Servizi</h2>
                </div>
                <ul class="menu p-4">
                    <li>
                        <a href="index.php?page=dashboard" class="<?php echo ($page == 'dashboard') ? 'active' : ''; ?>">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="index.php?page=pagamenti" class="<?php echo ($page == 'pagamenti') ? 'active' : ''; ?>">
                            <i class="fas fa-money-bill"></i> Pagamenti
                        </a>
                    </li>
                    <li>
                        <a href="index.php?page=telefonia" class="<?php echo ($page == 'telefonia') ? 'active' : ''; ?>">
                            <i class="fas fa-mobile-alt"></i> Telefonia
                        </a>
                    </li>
                    <li>
                        <a href="index.php?page=energia" class="<?php echo ($page == 'energia') ? 'active' : ''; ?>">
                            <i class="fas fa-bolt"></i> Luce e Gas
                        </a>
                    </li>
                    <li>
                        <a href="index.php?page=spedizioni" class="<?php echo ($page == 'spedizioni') ? 'active' : ''; ?>">
                            <i class="fas fa-shipping-fast"></i> Spedizioni
                        </a>
                    </li>
                    <li>
                        <a href="index.php?page=servizi-digitali" class="<?php echo ($page == 'servizi-digitali') ? 'active' : ''; ?>">
                            <i class="fas fa-fingerprint"></i> Servizi Digitali
                        </a>
                    </li>
                    <li>
                        <a href="index.php?page=clienti" class="<?php echo ($page == 'clienti') ? 'active' : ''; ?>">
                            <i class="fas fa-users"></i> Clienti
                        </a>
                    </li>
                    <?php if (hasRole('admin')): ?>
                    <li>
                        <a href="index.php?page=utenti" class="<?php echo ($page == 'utenti') ? 'active' : ''; ?>">
                            <i class="fas fa-user-cog"></i> Gestione Utenti
                        </a>
                    </li>
                    <?php endif; ?>
                    <li class="mt-4">
                        <a href="logout.php" class="text-error">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <footer class="footer mt-auto py-3 border-top">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <span class="text-muted">&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Tutti i diritti riservati.</span>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <span class="text-muted">Version 1.0.0</span>
                </div>
            </div>
        </div>
    </footer>
    </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="assets/js/script.js"></script>
    <script>
        // Verifica se ci sono messaggi toast da mostrare
        document.addEventListener('DOMContentLoaded', function() {
            const toastElList = [].slice.call(document.querySelectorAll('.toast'));
            const toastList = toastElList.map(function(toastEl) {
                return new bootstrap.Toast(toastEl, {
                    autohide: true,
                    delay: 5000
                });
            });
            toastList.forEach(toast => toast.show());
        });
        
        // Sidebar toggle per versione mobile
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
            const sidebar = document.getElementById('sidebar');
            
            if (sidebarToggleBtn) {
                sidebarToggleBtn.addEventListener('click', function() {
                    document.body.classList.toggle('sidebar-toggled');
                    sidebar.classList.toggle('show');
                });
            }
        });
        
        // Gestione tema chiaro/scuro
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            if (themeToggle) {
                themeToggle.addEventListener('change', function() {
                    if (this.checked) {
                        document.documentElement.setAttribute('data-theme', 'dark');
                        document.body.classList.add('dark-theme');
                        localStorage.setItem('theme', 'dark');
                    } else {
                        document.documentElement.setAttribute('data-theme', 'light');
                        document.body.classList.remove('dark-theme');
                        localStorage.setItem('theme', 'light');
                    }
                });

                // Carica il tema salvato
                const savedTheme = localStorage.getItem('theme');
                if (savedTheme === 'dark') {
                    themeToggle.checked = true;
                    document.documentElement.setAttribute('data-theme', 'dark');
                    document.body.classList.add('dark-theme');
                }
            }
        });
    </script>
</body>
</html>
