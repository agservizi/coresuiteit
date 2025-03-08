CREATE TABLE IF NOT EXISTS `servizi_digitali` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` INT(11) NOT NULL,
  `tipo_servizio` ENUM('SPID', 'PEC', 'Firma Digitale') NOT NULL,
  `descrizione` TEXT DEFAULT NULL,
  `stato` ENUM('Completato', 'In elaborazione', 'Annullato') NOT NULL DEFAULT 'In elaborazione',
  `data_creazione` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`cliente_id`) REFERENCES `clienti`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
