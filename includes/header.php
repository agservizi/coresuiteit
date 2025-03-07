<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Gestionale</title>
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@4.0.0/dist/tailwind.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <nav id="sidebar" class="w-64 bg-gray-800 text-white h-screen">
            <div class="p-4">
                <div class="text-center mb-4">
                    <img src="assets/img/logo.png" alt="Agenzia Servizi" class="mx-auto w-24 h-24">
                </div>
                <ul class="space-y-2">
                    <li>
                        <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 <?php echo ($page == 'dashboard') ? 'bg-gray-700' : ''; ?>" href="index.php?page=dashboard">
                            <i class="fas fa-home mr-2"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 <?php echo ($page == 'pagamenti') ? 'bg-gray-700' : ''; ?>" href="index.php?page=pagamenti">
                            <i class="fas fa-money-bill mr-2"></i> Pagamenti
                        </a>
                    </li>
                    <li>
                        <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 <?php echo ($page == 'telefonia') ? 'bg-gray-700' : ''; ?>" href="index.php?page=telefonia">
                            <i class="fas fa-mobile-alt mr-2"></i> Telefonia
                        </a>
                    </li>
                    <li>
                        <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 <?php echo ($page == 'energia') ? 'bg-gray-700' : ''; ?>" href="index.php?page=energia">
                            <i class="fas fa-bolt mr-2"></i> Luce e Gas
                        </a>
                    </li>
                    <li>
                        <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 <?php echo ($page == 'spedizioni') ? 'bg-gray-700' : ''; ?>" href="index.php?page=spedizioni">
                            <i class="fas fa-shipping-fast mr-2"></i> Spedizioni
                        </a>
                    </li>
                    <li>
                        <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 <?php echo ($page == 'servizi-digitali') ? 'bg-gray-700' : ''; ?>" href="index.php?page=servizi-digitali">
                            <i class="fas fa-fingerprint mr-2"></i> Servizi Digitali
                        </a>
                    </li>
                    <li>
                        <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 <?php echo ($page == 'clienti') ? 'bg-gray-700' : ''; ?>" href="index.php?page=clienti">
                            <i class="fas fa-users mr-2"></i> Clienti
                        </a>
                    </li>
                    <?php if (hasRole('admin')): ?>
                    <li>
                        <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 <?php echo ($page == 'utenti') ? 'bg-gray-700' : ''; ?>" href="index.php?page=utenti">
                            <i class="fas fa-user-cog mr-2"></i> Gestione Utenti
                        </a>
                    </li>
                    <?php endif; ?>
                    <li class="mt-5">
                        <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-red-700 text-red-500" href="logout.php">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main content -->
        <main class="flex-1 p-6">
            <div class="flex justify-between items-center border-b pb-4 mb-6">
                <h1 class="text-2xl font-semibold">
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
                <div class="relative">
                    <button class="bg-gray-800 text-white py-2 px-4 rounded focus:outline-none" id="userMenuButton">
                        <i class="fas fa-user mr-2"></i> <?php echo $_SESSION['user_name']; ?>
                    </button>
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded shadow-lg py-2 hidden" id="userMenu">
                        <a href="index.php?page=profilo" class="block px-4 py-2 text-gray-800 hover:bg-gray-200"><i class="fas fa-user-edit mr-2"></i> Profilo</a>
                        <a href="index.php?page=impostazioni" class="block px-4 py-2 text-gray-800 hover:bg-gray-200"><i class="fas fa-cog mr-2"></i> Impostazioni</a>
                        <div class="border-t my-2"></div>
                        <a href="logout.php" class="block px-4 py-2 text-red-500 hover:bg-gray-200"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a>
                    </div>
                </div>
            </div>
</main>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
