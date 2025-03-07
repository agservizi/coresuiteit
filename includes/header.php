<!DOCTYPE html>
<html lang="it" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Gestionale</title>
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@4.0.0/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.0.0/dist/full.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-base-200">
    <div class="drawer lg:drawer-open">
        <input id="drawer-toggle" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col">
            <!-- Navbar -->
            <div class="navbar bg-base-100 shadow-md z-10">
                <div class="flex-none lg:hidden">
                    <label for="drawer-toggle" class="btn btn-square btn-ghost">
                        <i class="fas fa-bars"></i>
                    </label>
                </div>
                <div class="flex-1">
                    <span class="text-xl font-semibold">
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
                    </span>
                </div>
                <div class="flex-none">
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-ghost">
                            <div class="avatar placeholder">
                                <div class="bg-neutral text-neutral-content rounded-full w-10">
                                    <span><?php echo substr($_SESSION['user_name'], 0, 1); ?></span>
                                </div>
                            </div>
                            <span class="ml-2 hidden md:inline"><?php echo $_SESSION['user_name']; ?></span>
                            <i class="fas fa-chevron-down ml-1"></i>
                        </div>
                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52 mt-4">
                            <li><a href="index.php?page=profilo"><i class="fas fa-user-edit mr-2"></i> Profilo</a></li>
                            <li><a href="index.php?page=impostazioni"><i class="fas fa-cog mr-2"></i> Impostazioni</a></li>
                            <li><hr class="my-1"></li>
                            <li><a href="logout.php" class="text-error"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Page content -->
            <div class="p-4">
</main>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
