<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Gestionale</title>
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <img src="assets/img/logo.png" alt="Agenzia Servizi" class="img-fluid sidebar-logo">
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white <?php echo ($page == 'dashboard') ? 'active' : ''; ?>" href="index.php?page=dashboard">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white <?php echo ($page == 'pagamenti') ? 'active' : ''; ?>" href="index.php?page=pagamenti">
                                <i class="fas fa-money-bill me-2"></i> Pagamenti
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white <?php echo ($page == 'telefonia') ? 'active' : ''; ?>" href="index.php?page=telefonia">
                                <i class="fas fa-mobile-alt me-2"></i> Telefonia
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white <?php echo ($page == 'energia') ? 'active' : ''; ?>" href="index.php?page=energia">
                                <i class="fas fa-bolt me-2"></i> Luce e Gas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white <?php echo ($page == 'spedizioni') ? 'active' : ''; ?>" href="index.php?page=spedizioni">
                                <i class="fas fa-shipping-fast me-2"></i> Spedizioni
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white <?php echo ($page == 'servizi-digitali') ? 'active' : ''; ?>" href="index.php?page=servizi-digitali">
                                <i class="fas fa-fingerprint me-2"></i> Servizi Digitali
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white <?php echo ($page == 'clienti') ? 'active' : ''; ?>" href="index.php?page=clienti">
                                <i class="fas fa-users me-2"></i> Clienti
                            </a>
                        </li>
                        <?php if (hasRole('admin')): ?>
                        <li class="nav-item">
                            <a class="nav-link text-white <?php echo ($page == 'utenti') ? 'active' : ''; ?>" href="index.php?page=utenti">
                                <i class="fas fa-user-cog me-2"></i> Gestione Utenti
                            </a>
                        </li>
                        <?php endif; ?>
                        <li class="nav-item mt-5">
                            <a class="nav-link text-danger" href="logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <?php 
                        switch($page) {
                            case 'dashboard': echo 'Dashboard'; break;
                            case 'pagamenti': echo 'Gestione Pagamenti'; break;
                            case 'telefonia': echo 'Gestione Telefonia'; break;
                            case 'energia': echo 'Gestione Luce e Gas'; break;
                            case 'spedizioni': echo 'Gestione Spedizioni'; break;
                            case 'servizi-digitali': echo 'Servizi Digitali'; break;
                            case 'clienti': echo 'Gestione Clienti'; break;
                            case 'utenti': echo 'Gestione Utenti'; break;
                            case 'profilo': echo 'Profilo Utente'; break;
                            case 'impostazioni': echo 'Impostazioni Utente'; break;
                            default: echo 'Dashboard';
                        }
                        ?>
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-1"></i> <?php echo $_SESSION['user_name']; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                <li><a class="dropdown-item" href="index.php?page=profilo"><i class="fas fa-user-edit me-1"></i> Profilo</a></li>
                                <li><a class="dropdown-item" href="index.php?page=impostazioni"><i class="fas fa-cog me-1"></i> Impostazioni</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
