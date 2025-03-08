<!DOCTYPE html>
<html lang="it" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Gestionale</title>
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI+Variable:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- App Window Structure -->
    <div class="app-window">
        <!-- App Title Bar (Windows 11 style) -->
        <div class="app-title-bar">
            <div class="app-icon">
                <img src="assets/img/favicon.ico" alt="<?php echo SITE_NAME; ?>" width="16" height="16">
            </div>
            <div class="app-title"><?php echo SITE_NAME; ?> - Gestionale</div>
            <div class="win-controls">
                <button class="win-control-btn minimize">
                    <i class="fas fa-minus"></i>
                </button>
                <button class="win-control-btn maximize">
                    <i class="fas fa-square"></i>
                </button>
                <button class="win-control-btn close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <!-- App Content -->
        <div class="app-content">
            <!-- Sidebar -->
            <aside id="sidebar" class="app-sidebar">
                <div class="sidebar-header">
                    <img src="assets/img/logo.png" alt="<?php echo SITE_NAME; ?>" class="sidebar-logo">
                    <h1 class="sidebar-title">CoreSuite</h1>
                </div>
                
                <div class="sidebar-nav">
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'dashboard') ? 'active' : ''; ?>" href="index.php?page=dashboard">
                                <i class="fas fa-tachometer-alt nav-icon"></i>
                                <span class="nav-text">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'pagamenti') ? 'active' : ''; ?>" href="index.php?page=pagamenti">
                                <i class="fas fa-money-bill-wave nav-icon"></i>
                                <span class="nav-text">Pagamenti</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'telefonia') ? 'active' : ''; ?>" href="index.php?page=telefonia">
                                <i class="fas fa-mobile-alt nav-icon"></i>
                                <span class="nav-text">Telefonia</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'energia') ? 'active' : ''; ?>" href="index.php?page=energia">
                                <i class="fas fa-bolt nav-icon"></i>
                                <span class="nav-text">Luce e Gas</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'spedizioni') ? 'active' : ''; ?>" href="index.php?page=spedizioni">
                                <i class="fas fa-shipping-fast nav-icon"></i>
                                <span class="nav-text">Spedizioni</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'servizi-digitali') ? 'active' : ''; ?>" href="index.php?page=servizi-digitali">
                                <i class="fas fa-fingerprint nav-icon"></i>
                                <span class="nav-text">Servizi Digitali</span>
                            </a>
                        </li>
                        
                        <li class="nav-separator"></li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'clienti') ? 'active' : ''; ?>" href="index.php?page=clienti">
                                <i class="fas fa-users nav-icon"></i>
                                <span class="nav-text">Clienti</span>
                            </a>
                        </li>
                        <?php if (hasRole('admin')): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'utenti') ? 'active' : ''; ?>" href="index.php?page=utenti">
                                <i class="fas fa-user-cog nav-icon"></i>
                                <span class="nav-text">Gestione Utenti</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($page == 'fatture') ? 'active' : ''; ?>" href="index.php?page=fatture">
                                <i class="fas fa-file-invoice-dollar nav-icon"></i>
                                <span class="nav-text">Fatturazione</span>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <!-- Theme Toggle -->
                <div class="theme-switch-wrapper">
                    <span class="theme-icon"><i class="fas fa-sun"></i></span>
                    <label class="theme-switch">
                        <input type="checkbox" id="themeToggle">
                        <span class="theme-switch-slider"></span>
                    </label>
                    <span class="theme-icon"><i class="fas fa-moon"></i></span>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="main-content">
                <!-- Top navbar -->
                <nav class="app-navbar">
                    <div class="page-title">
                        <?php 
                        switch($page) {
                            case 'dashboard': echo '<i class="fas fa-tachometer-alt"></i> Dashboard'; break;
                            case 'pagamenti': echo '<i class="fas fa-money-bill"></i> Gestione Pagamenti'; break;
                            case 'telefonia': echo '<i class="fas fa-mobile-alt"></i> Gestione Telefonia'; break;
                            case 'energia': echo '<i class="fas fa-bolt"></i> Gestione Luce e Gas'; break;
                            case 'spedizioni': echo '<i class="fas fa-shipping-fast"></i> Gestione Spedizioni'; break;
                            case 'servizi-digitali': echo '<i class="fas fa-fingerprint"></i> Servizi Digitali'; break;
                            case 'clienti': echo '<i class="fas fa-users"></i> Gestione Clienti'; break;
                            case 'utenti': echo '<i class="fas fa-user-cog"></i> Gestione Utenti'; break;
                            case 'fatture': echo '<i class="fas fa-file-invoice-dollar"></i> Fatturazione'; break;
                            case 'profilo': echo '<i class="fas fa-user-edit"></i> Profilo Utente'; break;
                            case 'impostazioni': echo '<i class="fas fa-cog"></i> Impostazioni Utente'; break;
                            default: echo '<i class="fas fa-tachometer-alt"></i> Dashboard';
                        }
                        ?>
                    </div>
                    
                    <div class="navbar-actions">
                        <!-- Notification Button -->
                        <div class="navbar-notification">
                            <button class="win11-btn win11-btn-icon" id="notificationBtn">
                                <i class="fas fa-bell"></i>
                                <span class="notification-badge">3</span>
                            </button>
                            <div class="notification-panel" id="notificationPanel">
                                <div class="notification-header">
                                    <h6>Notifiche</h6>
                                    <a href="#" class="mark-all">Segna tutte come lette</a>
                                </div>
                                <div class="notification-body">
                                    <a href="#" class="notification-item unread">
                                        <div class="notification-icon bg-primary">
                                            <i class="fas fa-user-plus"></i>
                                        </div>
                                        <div class="notification-content">
                                            <p class="notification-text">Nuovo cliente registrato</p>
                                            <span class="notification-time">2 minuti fa</span>
                                        </div>
                                    </a>
                                    <a href="#" class="notification-item unread">
                                        <div class="notification-icon bg-success">
                                            <i class="fas fa-money-bill"></i>
                                        </div>
                                        <div class="notification-content">
                                            <p class="notification-text">Pagamento completato #54238</p>
                                            <span class="notification-time">15 minuti fa</span>
                                        </div>
                                    </a>
                                    <a href="#" class="notification-item unread">
                                        <div class="notification-icon bg-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </div>
                                        <div class="notification-content">
                                            <p class="notification-text">Sistema aggiornato alla versione 1.0.2</p>
                                            <span class="notification-time">1 ora fa</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="notification-footer">
                                    <a href="#">Visualizza tutte</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Profile Button -->
                        <div class="navbar-user">
                            <button class="user-toggle" id="userProfileBtn">
                                <div class="user-avatar">
                                    <img src="assets/img/avatar.jpg" alt="User" class="avatar-img">
                                </div>
                                <div class="user-info d-none d-sm-block">
                                    <span class="user-name"><?php echo $_SESSION['user_name']; ?></span>
                                    <span class="user-role"><?php echo (hasRole('admin')) ? 'Amministratore' : 'Operatore'; ?></span>
                                </div>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="user-panel" id="userPanel">
                                <div class="user-panel-header">
                                    <div class="user-avatar">
                                        <img src="assets/img/avatar.jpg" alt="User" class="avatar-img">
                                    </div>
                                    <div class="user-info">
                                        <span class="user-name"><?php echo $_SESSION['user_name']; ?></span>
                                        <span class="user-email"><?php echo $_SESSION['user_email']; ?></span>
                                    </div>
                                </div>
                                <div class="user-panel-body">
                                    <a class="panel-item" href="index.php?page=profilo">
                                        <i class="fas fa-user-edit"></i> Profilo
                                    </a>
                                    <a class="panel-item" href="index.php?page=impostazioni">
                                        <i class="fas fa-cog"></i> Impostazioni
                                    </a>
                                </div>
                                <div class="user-panel-footer">
                                    <a class="panel-item text-danger" href="logout.php">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
                
                <!-- Content area -->
                <div class="content">
                <!-- ...existing code... -->
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
