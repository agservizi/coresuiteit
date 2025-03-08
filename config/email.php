<?php
// Configurazioni per l'invio delle email

// Server SMTP
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
define('SMTP_AUTH', true);
define('SMTP_USERNAME', 'ag.servizi16@gmail.com');
define('SMTP_PASSWORD', 'password_app'); // Utilizza una password per applicazioni se usi Gmail

// Informazioni mittente
define('EMAIL_FROM', 'ag.servizi16@gmail.com');
define('NAME_FROM', 'CoreSuite IT');

// Configurazione template email
define('EMAIL_TEMPLATE_PATH', __DIR__ . '/../templates/email/');

// Impostazioni generali
define('EMAIL_DEBUG', 0); // 0 = off, 1 = client messages, 2 = client and server messages
define('EMAIL_CHARSET', 'UTF-8');
