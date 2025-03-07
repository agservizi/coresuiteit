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
    
    <script src="https://cdn.jsdelivr.net/npm/tailwindcss@4.0.0/dist/tailwind.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="assets/js/script.js"></script>
    <script>
        // Toggle theme
        function toggleTheme() {
            const html = document.querySelector('html');
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        }

        // Check for saved theme preference
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                document.querySelector('html').setAttribute('data-theme', savedTheme);
            }
        });
    </script>
</body>
</html>
