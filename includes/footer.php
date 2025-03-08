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
    </div> <!-- End content-wrapper -->
            
    <!-- Footer -->
    <footer class="app-footer">
        <div class="footer-content">
            <div class="footer-copyright">
                <span>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Tutti i diritti riservati.</span>
            </div>
            <div class="footer-links">
                <a href="#" class="footer-link">Privacy</a>
                <a href="#" class="footer-link">Termini</a>
                <a href="#" class="footer-link">Supporto</a>
            </div>
        </div>
    </footer>
    </main> <!-- End app-main -->
    </div> <!-- End app-container -->
    
    <!-- Windows 11 Taskbar -->
    <div class="win11-taskbar">
        <div class="win11-taskbar-content">
            <button class="win11-start-button" id="startMenuBtn">
                <i class="fab fa-windows"></i>
            </button>
            
            <div class="taskbar-app-icons">
                <button class="taskbar-icon active" title="CoreSuite">
                    <i class="fas fa-cube"></i>
                </button>
                <button class="taskbar-icon" title="File Explorer">
                    <i class="fas fa-folder"></i>
                </button>
                <button class="taskbar-icon" title="Microsoft Edge">
                    <i class="fab fa-edge"></i>
                </button>
                <button class="taskbar-icon" title="Microsoft Store">
                    <i class="fas fa-shopping-bag"></i>
                </button>
                <button class="taskbar-icon" title="Mail">
                    <i class="fas fa-envelope"></i>
                </button>
            </div>
            
            <div class="win11-task-right">
                <button class="taskbar-icon" title="Lingua">
                    <span>IT</span>
                </button>
                <button class="taskbar-icon" title="Rete">
                    <i class="fas fa-wifi"></i>
                </button>
                <button class="taskbar-icon" title="Volume">
                    <i class="fas fa-volume-up"></i>
                </button>
                <button class="taskbar-icon" id="taskbarTime" title="Data e ora">
                    <?php echo date('H:i'); ?>
                </button>
                <button class="taskbar-icon" title="Notifiche">
                    <i class="fas fa-comment"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Start Menu Panel -->
    <div class="win11-start-panel" id="startMenuPanel">
        <div class="start-panel-header">
            <div class="search-container">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Digita qui per cercare" class="search-input">
            </div>
        </div>
        
        <div class="start-panel-pinned">
            <div class="start-panel-section-title">
                <span>Aggiunti</span>
                <button class="win11-btn-text">Tutte le app <i class="fas fa-chevron-right"></i></button>
            </div>
            
            <div class="start-panel-apps">
                <a href="index.php?page=dashboard" class="app-item">
                    <div class="app-icon bg-primary">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <span>Dashboard</span>
                </a>
                <a href="index.php?page=pagamenti" class="app-item">
                    <div class="app-icon bg-success">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <span>Pagamenti</span>
                </a>
                <a href="index.php?page=clienti" class="app-item">
                    <div class="app-icon bg-info">
                        <i class="fas fa-users"></i>
                    </div>
                    <span>Clienti</span>
                </a>
                <a href="index.php?page=telefonia" class="app-item">
                    <div class="app-icon bg-warning">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <span>Telefonia</span>
                </a>
                <a href="index.php?page=energia" class="app-item">
                    <div class="app-icon bg-danger">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <span>Energia</span>
                </a>
                <a href="index.php?page=spedizioni" class="app-item">
                    <div class="app-icon bg-secondary">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <span>Spedizioni</span>
                </a>
                <a href="index.php?page=servizi-digitali" class="app-item">
                    <div class="app-icon bg-dark">
                        <i class="fas fa-fingerprint"></i>
                    </div>
                    <span>Servizi</span>
                </a>
                <?php if (hasRole('admin')): ?>
                <a href="index.php?page=utenti" class="app-item">
                    <div class="app-icon bg-primary-dark">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <span>Utenti</span>
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="start-panel-recommended">
            <div class="start-panel-section-title">
                <span>Consigliati</span>
                <button class="win11-btn-text">Altro <i class="fas fa-chevron-right"></i></button>
            </div>
            
            <div class="start-panel-recent">
                <div class="recent-item">
                    <div class="recent-icon">
                        <i class="far fa-file-alt"></i>
                    </div>
                    <div class="recent-content">
                        <span class="recent-title">Report Mensile</span>
                        <span class="recent-desc">Modificato di recente</span>
                    </div>
                    <div class="recent-time">14:30</div>
                </div>
                <div class="recent-item">
                    <div class="recent-icon">
                        <i class="far fa-user"></i>
                    </div>
                    <div class="recent-content">
                        <span class="recent-title">Mario Rossi</span>
                        <span class="recent-desc">Cliente</span>
                    </div>
                    <div class="recent-time">Ieri</div>
                </div>
            </div>
        </div>
        
        <div class="start-panel-footer">
            <div class="user-profile">
                <div class="user-avatar">
                    <img src="assets/img/avatar.jpg" alt="User" class="avatar-img">
                </div>
                <span class="user-name"><?php echo $_SESSION['user_name']; ?></span>
            </div>
            <button class="win11-btn-icon power-button" title="Spegni">
                <i class="fas fa-power-off"></i>
            </button>
        </div>
    </div>
    
    <!-- Toast container for notifications -->
    <div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3"></div>
    
    <!-- JavaScript Libraries -->
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
        
        // Windows 11 specifico
        document.addEventListener('DOMContentLoaded', function() {
            // Gestione del menu Start
            const startMenuBtn = document.getElementById('startMenuBtn');
            const startMenuPanel = document.getElementById('startMenuPanel');
            
            startMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                startMenuPanel.classList.toggle('show');
                
                // Aggiungi la classe active al pulsante Start
                startMenuBtn.classList.toggle('active');
            });
            
            // Chiudi il menu start quando si fa clic altrove
            document.addEventListener('click', function(e) {
                if (!startMenuPanel.contains(e.target) && e.target !== startMenuBtn) {
                    startMenuPanel.classList.remove('show');
                    startMenuBtn.classList.remove('active');
                }
            });
            
            // Gestione notifiche
            const notificationBtn = document.getElementById('notificationBtn');
            const notificationPanel = document.getElementById('notificationPanel');
            
            notificationBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                notificationPanel.classList.toggle('show');
            });
            
            document.addEventListener('click', function(e) {
                if (!notificationPanel.contains(e.target) && e.target !== notificationBtn) {
                    notificationPanel.classList.remove('show');
                }
            });
            
            // Gestione profilo utente
            const userProfileBtn = document.getElementById('userProfileBtn');
            const userPanel = document.getElementById('userPanel');
            
            userProfileBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                userPanel.classList.toggle('show');
            });
            
            document.addEventListener('click', function(e) {
                if (!userPanel.contains(e.target) && e.target !== userProfileBtn) {
                    userPanel.classList.remove('show');
                }
            });
            
            // Aggiorna l'orologio nella taskbar
            function updateTaskbarClock() {
                const taskbarTime = document.getElementById('taskbarTime');
                const now = new Date();
                let hours = now.getHours().toString().padStart(2, '0');
                let minutes = now.getMinutes().toString().padStart(2, '0');
                taskbarTime.textContent = hours + ':' + minutes;
            }
            
            // Aggiorna l'orologio ogni minuto
            updateTaskbarClock();
            setInterval(updateTaskbarClock, 60000);
            
            // Imposta effetti trasparenza al passaggio del mouse
            document.querySelectorAll('.win11-card, .app-navbar').forEach(element => {
                element.addEventListener('mouseenter', function() {
                    this.style.backdropFilter = 'blur(30px) saturate(200%)';
                });
                
                element.addEventListener('mouseleave', function() {
                    this.style.backdropFilter = 'blur(20px) saturate(180%)';
                });
            });
            
            // Tema chiaro/scuro
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
                const savedTheme = localStorage.getItem('theme') || 'light';
                if (savedTheme === 'dark') {
                    themeToggle.checked = true;
                    document.documentElement.setAttribute('data-theme', 'dark');
                    document.body.classList.add('dark-theme');
                }
            }
            
            // Pulsanti finestra
            document.querySelector('.win-control-btn.minimize').addEventListener('click', function() {
                // Simula la minimizzazione
                document.querySelector('.app-window').classList.add('minimized');
                setTimeout(() => {
                    document.querySelector('.app-window').classList.remove('minimized');
                }, 300);
            });
            
            document.querySelector('.win-control-btn.maximize').addEventListener('click', function() {
                document.querySelector('.app-window').classList.toggle('maximized');
                const icon = this.querySelector('i');
                if (document.querySelector('.app-window').classList.contains('maximized')) {
                    icon.classList.replace('fa-square', 'fa-clone');
                } else {
                    icon.classList.replace('fa-clone', 'fa-square');
                }
            });
            
            document.querySelector('.win-control-btn.close').addEventListener('click', function() {
                // Simula la chiusura (diminuisce l'opacità e poi riporta la finestra normale)
                document.querySelector('.app-window').classList.add('closing');
                setTimeout(() => {
                    document.querySelector('.app-window').classList.remove('closing');
                }, 500);
            });
        });
    </script>
</body>
</html>
