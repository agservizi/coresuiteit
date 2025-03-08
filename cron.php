<?php
/**
 * Script per l'esecuzione di task schedulati (es. invio promemoria)
 */

require_once 'config/config.php';
require_once 'includes/scheduler.php';

// Puoi aggiungere altri task qui

// Esegui la funzione di invio promemoria
sendAppointmentReminders();
