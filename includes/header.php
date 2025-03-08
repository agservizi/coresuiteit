<?php
require_once 'config/config.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Avvia la sessione
session_start();

// Ottieni l'URL della pagina corrente
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="it" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - <?php echo ucfirst(str_replace(['-', '_'], ' ', $current_page)); ?></title>
    
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- DaisyUI -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.24/dist/full.min.css" rel="stylesheet" type="text/css" />
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-100 font-sans antialiased">

<div class="drawer">
  <input id="my-drawer-3" type="checkbox" class="drawer-toggle" /> 
  <div class="drawer-content flex flex-col">
    <!-- Navbar -->
    <div class="w-full navbar bg-base-300 text-base-content">
      
      <!-- Hamburger menu (mobile) -->
      <div class="flex-none lg:hidden">
        <label for="my-drawer-3" aria-label="open sidebar" class="btn btn-square btn-ghost">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-6 h-6 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </label>
      </div>
      
      <!-- Logo -->
      <div class="flex-1 px-2 mx-2">
        <a href="index.php" class="btn btn-ghost text-xl"><?php echo SITE_NAME; ?></a>
      </div>
      
      <!-- Navbar links (desktop) -->
      <div class="flex-none hidden lg:flex">
        <ul class="menu menu-horizontal">
          <!-- Navbar items -->
          <li><a href="index.php">Dashboard</a></li>
          <li><a href="clienti.php">Clienti</a></li>
          <li><a href="pagamenti.php">Pagamenti</a></li>
          <li><a href="telefonia.php">Telefonia</a></li>
          <li><a href="energia.php">Energia</a></li>
          <li><a href="spedizioni.php">Spedizioni</a></li>
          <li><a href="fatture.php">Fatture</a></li>
          <li><a href="documenti.php">Documenti</a></li>
          <li><a href="appuntamenti.php">Appuntamenti</a></li>
          <li><a href="servizi-digitali.php">Servizi Digitali</a></li>
          <li><a href="leads.php">Leads</a></li>
          <li><a href="campagne-marketing.php">Campagne Marketing</a></li>
          <li><a href="knowledge-base.php">Knowledge Base</a></li>
          <li><a href="supporto.php">Supporto</a></li>
          <li><a href="messaggi.php">Messaggi</a></li>
          <?php if (hasRole('admin')): ?>
            <li><a href="utenti.php">Utenti</a></li>
            <li><a href="audit-log.php">Audit Log</a></li>
            <li><a href="api-keys.php">API Keys</a></li>
            <li><a href="automazioni.php">Automazioni</a></li>
          <?php endif; ?>
        </ul>
      </div>
      
      <!-- User info and logout -->
      <?php if (isLoggedIn()): ?>
      <div class="flex-none">
        <div class="dropdown dropdown-end">
          <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
            <div class="w-10 rounded-full">
              <img alt="Avatar" src="assets/img/avatar.jpg" />
            </div>
          </div>
          <ul tabindex="0" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52">
            <li><a href="profilo.php">Profilo</a></li>
            <li><a href="impostazioni.php">Impostazioni</a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <!-- End Navbar -->
    
    <!-- Page Content -->
    <main class="container mx-auto py-5">
    </main>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>