# CoreSuite

CoreSuite è un sistema gestionale per la gestione di clienti, pagamenti, contratti di energia, telefonia, spedizioni e servizi digitali.

## Requisiti

- PHP 7.4 o superiore
- MySQL 5.7 o superiore
- Composer
- Web server (Apache, Nginx, ecc.)

## Installazione

1. Clona il repository:

   ```bash
   git clone https://github.com/tuo-username/coresuite.git
   cd coresuite
   ```

2. Installa le dipendenze con Composer:

   ```bash
   composer install
   ```

3. Configura il database:

   - Crea un database MySQL.
   - Importa il file `database/schema.sql` nel database creato.
   - (Opzionale) Importa il file `database/create_admin_user.sql` per creare un utente amministratore predefinito.

4. Configura il file `config/config.php` con le informazioni del database:

   ```php
   // filepath: c:\Users\ASUS\Documents\GitHub\coresuite\config\config.php
   <?php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'nome_database');
   define('DB_USER', 'nome_utente');
   define('DB_PASS', 'password');
   define('SITE_NAME', 'CoreSuite');
   ```

5. Configura il web server per puntare alla directory del progetto. Se usi Apache, puoi utilizzare il file `.htaccess` incluso.

## Uso

1. Avvia il server web e accedi all'applicazione tramite il browser.
2. Effettua il login con le credenziali dell'utente amministratore.
3. Utilizza il menu di navigazione per accedere alle diverse sezioni del gestionale.

## Struttura del Progetto

- `config/`: Configurazioni del progetto.
- `includes/`: Funzioni e script di autenticazione.
- `pages/`: Pagine principali dell'applicazione.
- `assets/`: File CSS, JavaScript e immagini.
- `database/`: Script SQL per la creazione del database.
- `api/`: Endpoint API per ottenere dati.

## API

### Endpoint disponibili

- `GET /api/get_transaction_data.php`: Ottiene i dati delle transazioni degli ultimi 7 giorni.
- `GET /api/get_service_data.php`: Ottiene i dati dei servizi raggruppati per tipo.

### Esempio di richiesta

```bash
curl -X GET https://tuo-dominio/api/get_transaction_data.php
```

### Esempio di risposta

```json
{
  "labels": [
    "2023-08-01",
    "2023-08-02",
    "2023-08-03",
    "2023-08-04",
    "2023-08-05",
    "2023-08-06",
    "2023-08-07"
  ],
  "data": [5, 10, 3, 8, 12, 7, 9]
}
```

## Deploy

1. Assicurati di avere un server web configurato con PHP e MySQL.
2. Carica i file del progetto sul server.
3. Configura il file `config/config.php` con le informazioni del database del server.
4. Importa il file `database/schema.sql` nel database del server.
5. (Opzionale) Importa il file `database/create_admin_user.sql` per creare un utente amministratore predefinito.
6. Accedi all'applicazione tramite il browser e verifica che tutto funzioni correttamente.

## Contribuire

1. Fai un fork del repository.
2. Crea un nuovo branch per la tua feature o bugfix.
3. Fai un commit delle tue modifiche.
4. Fai un push del branch e apri una pull request.

## Licenza

Questo progetto è rilasciato sotto la licenza MIT. Vedi il file [LICENSE](LICENSE) per maggiori dettagli.
