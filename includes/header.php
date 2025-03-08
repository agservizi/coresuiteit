<!DOCTYPE html>
<html lang="it" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Gestionale</title>
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Mobile sidebar toggle -->
    <button id="sidebarToggleBtn" class="sidebar-toggle-btn d-md-none">
        <i class="fas fa-bars"></i>
    </button>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <img src="assets/img/logo.png" alt="Agenzia Servizi" class="sidebar-logo">
                    </div>
                    
                    <!-- Theme toggle -->
                    <div class="d-flex justify-content-center align-items-center mb-4">
                        <span class="theme-icon"><i class="fas fa-sun"></i></span>
                        <label class="theme-switch">
                            <input type="checkbox" id="themeToggle">
                            <span class="theme-switch-slider"></span>
                        </label>
                        <span class="theme-icon"><i class="fas fa-moon"></i></span>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'dashboard') ? 'active' : ''; ?>" href="index.php?page=dashboard">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'pagamenti') ? 'active' : ''; ?>" href="index.php?page=pagamenti">
                                <i class="fas fa-money-bill"></i> Pagamenti
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'telefonia') ? 'active' : ''; ?>" href="index.php?page=telefonia">
                                <i class="fas fa-mobile-alt"></i> Telefonia
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'energia') ? 'active' : ''; ?>" href="index.php?page=energia">
                                <i class="fas fa-bolt"></i> Luce e Gas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'spedizioni') ? 'active' : ''; ?>" href="index.php?page=spedizioni">
                                <i class="fas fa-shipping-fast"></i> Spedizioni
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'servizi-digitali') ? 'active' : ''; ?>" href="index.php?page=servizi-digitali">
                                <i class="fas fa-fingerprint"></i> Servizi Digitali
                            </a>
                        </li>
                        <li class="nav-item divider"></li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'clienti') ? 'active' : ''; ?>" href="index.php?page=clienti">
                                <i class="fas fa-users"></i> Clienti
                            </a>
                        </li>
                        <?php if (hasRole('admin')): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'utenti') ? 'active' : ''; ?>" href="index.php?page=utenti">
                                <i class="fas fa-user-cog"></i> Gestione Utenti
                            </a>
                        </li>
                        <?php endif; ?>
                        <li class="nav-item mt-5">
                            <a class="nav-link text-danger" href="logout.php">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom page-header">
                    <h1 class="h2">
                        <?php 
                        switch($page) {
                            case 'dashboard': echo '<i class="fas fa-tachometer-alt me-2"></i> Dashboard'; break;
                            case 'pagamenti': echo '<i class="fas fa-money-bill me-2"></i> Gestione Pagamenti'; break;
                            case 'telefonia': echo '<i class="fas fa-mobile-alt me-2"></i> Gestione Telefonia'; break;
                            case 'energia': echo '<i class="fas fa-bolt me-2"></i> Gestione Luce e Gas'; break;
                            case 'spedizioni': echo '<i class="fas fa-shipping-fast me-2"></i> Gestione Spedizioni'; break;
                            case 'servizi-digitali': echo '<i class="fas fa-fingerprint me-2"></i> Servizi Digitali'; break;
                            case 'clienti': echo '<i class="fas fa-users me-2"></i> Gestione Clienti'; break;
                            case 'utenti': echo '<i class="fas fa-user-cog me-2"></i> Gestione Utenti'; break;
                            case 'profilo': echo '<i class="fas fa-user-edit me-2"></i> Profilo Utente'; break;
                            case 'impostazioni': echo '<i class="fas fa-cog me-2"></i> Impostazioni Utente'; break;
                            default: echo '<i class="fas fa-tachometer-alt me-2"></i> Dashboard';
                        }
                        ?>
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-primary dropdown-toggle btn-icon" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user"></i> <?php echo $_SESSION['user_name']; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                <li><a class="dropdown-item" href="index.php?page=profilo"><i class="fas fa-user-edit me-2"></i> Profilo</a></li>
                                <li><a class="dropdown-item" href="index.php?page=impostazioni"><i class="fas fa-cog me-2"></i> Impostazioni</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                <!-- ...existing code... -->
                </div>
            </main>
        </div>
    </div>
    <!-- Footer -->
    <footer class="footer p-10 bg-base-200 text-base-content">
        <div>
            <span class="footer-title">Servizi</span> 
            <a class="link link-hover">Branding</a> 
            <a class="link link-hover">Design</a> 
            <a class="link link-hover">Marketing</a> 
            <a class="link link-hover">Advertisement</a>
        </div> 
        <div>
            <span class="footer-title">Azienda</span> 
            <a class="link link-hover">Chi siamo</a> 
            <a class="link link-hover">Contatti</a> 
            <a class="link link-hover">Lavora con noi</a> 
            <a class="link link-hover">Press kit</a>
        </div> 
        <div>
            <span class="footer-title">Legale</span> 
            <a class="link link-hover">Termini di utilizzo</a> 
            <a class="link link-hover">Privacy policy</a> 
            <a class="link link-hover">Cookie policy</a>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
