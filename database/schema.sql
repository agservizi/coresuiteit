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
