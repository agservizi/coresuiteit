-- Creazione del database
CREATE DATABASE IF NOT EXISTS `u427445037_coresuite`;
USE `u427445037_coresuite`;

-- Tabella utenti
CREATE TABLE IF NOT EXISTS `utenti` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(50) NOT NULL,
  `cognome` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `ruolo` ENUM('admin', 'operatore') NOT NULL DEFAULT 'operatore',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabella clienti
CREATE TABLE IF NOT EXISTS `clienti` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(50) NOT NULL,
  `cognome` VARCHAR(50) NOT NULL,
  `codice_fiscale` VARCHAR(16) DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `telefono` VARCHAR(20) NOT NULL,
  `indirizzo` VARCHAR(255) DEFAULT NULL,
  `citta` VARCHAR(50) DEFAULT NULL,
  `cap` VARCHAR(5) DEFAULT NULL,
  `note` TEXT DEFAULT NULL,
  `data_registrazione` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabella pagamenti
CREATE TABLE IF NOT EXISTS `pagamenti` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` VARCHAR(20) NOT NULL,
  `cliente_id` INT(11) NOT NULL,
  `tipo` ENUM('Bollettino', 'Bonifico', 'F24', 'PagoPA', 'MAV', 'RAV') NOT NULL,
  `importo` DECIMAL(10,2) NOT NULL,
  `descrizione` TEXT DEFAULT NULL,
  `stato` ENUM('Completato', 'In elaborazione', 'Annullato') NOT NULL DEFAULT 'In elaborazione',
  `data_creazione` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`cliente_id`) REFERENCES `clienti`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabella telefonia
CREATE TABLE IF NOT EXISTS `telefonia` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` INT(11) NOT NULL,
  `operatore` ENUM('Fastweb', 'Iliad', 'WindTre', 'TIM', 'Vodafone') NOT NULL,
  `tipologia` ENUM('Mobile', 'Fisso', 'Fisso+Mobile', 'Internet') NOT NULL,
  `numero_mobile` VARCHAR(20) DEFAULT NULL,
  `piano_tariffario` VARCHAR(100) NOT NULL,
  `costo_mensile` DECIMAL(10,2) NOT NULL,
  `note` TEXT DEFAULT NULL,
  `data_attivazione` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `stato` ENUM('Attivo', 'In attivazione', 'Disattivato') NOT NULL DEFAULT 'In attivazione',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`cliente_id`) REFERENCES `clienti`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabella energia
CREATE TABLE IF NOT EXISTS `energia` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` INT(11) NOT NULL,
  `fornitore` ENUM('A2A Energia', 'Enel Energia', 'WindTre Luce e Gas', 'ENI Plenitude', 'Sorgenia') NOT NULL,
  `tipologia` ENUM('Luce', 'Gas', 'Luce e Gas') NOT NULL,
  `indirizzo_fornitura` VARCHAR(255) NOT NULL,
  `citta_fornitura` VARCHAR(50) NOT NULL,
  `cap_fornitura` VARCHAR(5) NOT NULL,
  `pod_pdr` VARCHAR(20) NOT NULL,
  `offerta` VARCHAR(100) NOT NULL,
  `note` TEXT DEFAULT NULL,
  `data_attivazione` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `stato` ENUM('Attivo', 'In lavorazione', 'Annullato', 'Scaduto') NOT NULL DEFAULT 'In lavorazione',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`cliente_id`) REFERENCES `clienti`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabella spedizioni
CREATE TABLE IF NOT EXISTS `spedizioni` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` INT(11) NOT NULL,
  `tipo` ENUM('Nazionale', 'Internazionale') NOT NULL,
  `peso` DECIMAL(10,2) NOT NULL,
  `tracking_number` VARCHAR(30) NOT NULL,
  `destinatario_nome` VARCHAR(100) NOT NULL,
  `destinazione_indirizzo` VARCHAR(255) NOT NULL,
  `destinazione_citta` VARCHAR(50) NOT NULL,
  `destinazione_cap` VARCHAR(5) NOT NULL,
  `destinazione_paese` VARCHAR(50) NOT NULL,
  `importo` DECIMAL(10,2) NOT NULL,
  `note` TEXT DEFAULT NULL,
  `data_spedizione` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `stato` ENUM('In lavorazione', 'Spedito', 'Consegnato', 'Annullato') NOT NULL DEFAULT 'In lavorazione',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`cliente_id`) REFERENCES `clienti`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabella fatture
CREATE TABLE IF NOT EXISTS `fatture` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `fattura_id` VARCHAR(20) NOT NULL,
  `cliente_id` INT(11) NOT NULL,
  `importo` DECIMAL(10,2) NOT NULL,
  `descrizione` TEXT DEFAULT NULL,
  `stato` ENUM('Pagata', 'Non Pagata') NOT NULL DEFAULT 'Non Pagata',
  `data_creazione` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`cliente_id`) REFERENCES `clienti`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabella notifiche
CREATE TABLE IF NOT EXISTS `notifiche` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `utente_id` INT(11) NOT NULL,
  `tipo` ENUM('info', 'success', 'warning', 'error') NOT NULL DEFAULT 'info',
  `messaggio` TEXT NOT NULL,
  `link` VARCHAR(255) DEFAULT NULL,
  `data_creazione` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `letta` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`utente_id`) REFERENCES `utenti`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabella documenti
CREATE TABLE IF NOT EXISTS `documenti` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` INT(11) NOT NULL,
  `nome` VARCHAR(100) NOT NULL,
  `filename` VARCHAR(255) NOT NULL,
  `tipo` VARCHAR(50) NOT NULL,
  `note` TEXT DEFAULT NULL,
  `data_caricamento` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`cliente_id`) REFERENCES `clienti`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabella di reset password
CREATE TABLE IF NOT EXISTS `password_reset` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `utente_id` INT(11) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `scadenza` DATETIME NOT NULL,
  `data_creazione` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`utente_id`) REFERENCES `utenti`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabella appuntamenti
CREATE TABLE IF NOT EXISTS `appuntamenti` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` INT(11) NOT NULL,
  `utente_id` INT(11) DEFAULT NULL,
  `titolo` VARCHAR(255) NOT NULL,
  `descrizione` TEXT DEFAULT NULL,
  `data` DATE NOT NULL,
  `ora` TIME NOT NULL,
  `durata` INT(11) NOT NULL DEFAULT '60',
  `stato` ENUM('in attesa', 'confermato', 'cancellato') NOT NULL DEFAULT 'in attesa',
  `data_creazione` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`cliente_id`) REFERENCES `clienti`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`utente_id`) REFERENCES `utenti`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabella permessi
CREATE TABLE IF NOT EXISTS `permessi` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `descrizione` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabella permessi_ruoli (associazione tra ruoli e permessi)
CREATE TABLE IF NOT EXISTS `permessi_ruoli` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `ruolo` ENUM('admin', 'operatore') NOT NULL,
  `permesso_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`permesso_id`) REFERENCES `permessi`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabella tickets di supporto
CREATE TABLE IF NOT EXISTS `tickets_supporto` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` INT(11) NOT NULL,
  `utente_id` INT(11) DEFAULT NULL,
  `titolo` VARCHAR(255) NOT NULL,
  `descrizione` TEXT DEFAULT NULL,
  `priorita` ENUM('Bassa', 'Media', 'Alta', 'Urgente') NOT NULL DEFAULT 'Media',
  `stato` ENUM('Aperto', 'In corso', 'Chiuso') NOT NULL DEFAULT 'Aperto',
  `data_apertura` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_chiusura` TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`cliente_id`) REFERENCES `clienti`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`utente_id`) REFERENCES `utenti`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabella knowledge base
CREATE TABLE IF NOT EXISTS `knowledge_base` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `utente_id` INT(11) DEFAULT NULL,
  `titolo` VARCHAR(255) NOT NULL,
  `contenuto` TEXT DEFAULT NULL,
  `categoria` VARCHAR(100) NOT NULL,
  `data_creazione` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`utente_id`) REFERENCES `utenti`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabella messaggi
CREATE TABLE IF NOT EXISTS `messaggi` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `mittente_id` INT(11) NOT NULL,
  `destinatario_id` INT(11) NOT NULL,
  `messaggio` TEXT NOT NULL,
  `data_invio` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `letto` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`mittente_id`) REFERENCES `utenti`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`destinatario_id`) REFERENCES `utenti`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabella audit log
CREATE TABLE IF NOT EXISTS `audit_log` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) DEFAULT NULL,
  `action` VARCHAR(255) NOT NULL,
  `details` TEXT DEFAULT NULL,
  `table_name` VARCHAR(100) DEFAULT NULL,
  `record_id` INT(11) DEFAULT NULL,
  `ip_address` VARCHAR(50) DEFAULT NULL,
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `utenti`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabella API keys
CREATE TABLE IF NOT EXISTS `api_keys` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `api_key` VARCHAR(255) NOT NULL,
  `data_creazione` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `utenti`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabella campagne marketing
CREATE TABLE IF NOT EXISTS `campagne_marketing` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `utente_id` INT(11) DEFAULT NULL,
  `nome` VARCHAR(255) NOT NULL,
  `descrizione` TEXT DEFAULT NULL,
  `tipo` ENUM('Email', 'SMS', 'Social') NOT NULL,
  `destinatari` ENUM('Tutti', 'Clienti', 'Leads') NOT NULL,
  `contenuto` TEXT DEFAULT NULL,
  `data_creazione` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`utente_id`) REFERENCES `utenti`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabella leads
CREATE TABLE IF NOT EXISTS `leads` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(50) NOT NULL,
  `cognome` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `telefono` VARCHAR(20) NOT NULL,
  `fonte` VARCHAR(100) DEFAULT NULL,
  `note` TEXT DEFAULT NULL,
  `data_registrazione` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabella automazioni
CREATE TABLE IF NOT EXISTS `automazioni` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `utente_id` INT(11) DEFAULT NULL,
  `nome` VARCHAR(255) NOT NULL,
  `descrizione` TEXT DEFAULT NULL,
  `trigger_evento` VARCHAR(100) NOT NULL,
  `azione` VARCHAR(255) NOT NULL,
  `condizioni` TEXT DEFAULT NULL,
  `data_creazione` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`utente_id`) REFERENCES `utenti`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
