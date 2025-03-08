<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benvenuto su CoreSuite</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #0078d4;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #888;
        }
        .button {
            display: inline-block;
            background-color: #0078d4;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Benvenuto su CoreSuite</h1>
        </div>
        <div class="content">
            <p>Gentile <?php echo $nome . ' ' . $cognome; ?>,</p>
            <p>Ti diamo il benvenuto su CoreSuite! Il tuo account è stato creato con successo.</p>
            <p>Da ora potrai accedere a tutti i servizi offerti dalla nostra piattaforma:</p>
            <ul>
                <li>Gestione contratti energia</li>
                <li>Servizi di telefonia</li>
                <li>Spedizioni e logistica</li>
                <li>Servizi di pagamento</li>
                <li>E molto altro!</li>
            </ul>
            <p>Per accedere alla tua area personale, utilizza il seguente link:</p>
            <p style="text-align: center;">
                <a href="<?php echo isset($loginUrl) ? $loginUrl : 'https://coresuite.it/login.php'; ?>" class="button">Accedi al tuo account</a>
            </p>
            <p>Grazie per la fiducia accordata alla nostra azienda. Siamo a tua disposizione per qualsiasi necessità.</p>
            <p>Cordiali saluti,<br>Il team di CoreSuite</p>
        </div>
        <div class="footer">
            <p>© <?php echo date('Y'); ?> CoreSuite IT - Tutti i diritti riservati.</p>
            <p>Ricevi questa email perché sei registrato sulla piattaforma CoreSuite.</p>
        </div>
    </div>
</body>
</html>
