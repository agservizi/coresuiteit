<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recupero Password - CoreSuite</title>
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
        .button {
            display: inline-block;
            background-color: #0078d4;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 15px;
        }
        .warning {
            background-color: #fff8e1;
            border-left: 4px solid #ffc107;
            padding: 10px 15px;
            margin: 15px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Recupero Password</h1>
        </div>
        <div class="content">
            <p>Gentile Utente,</p>
            <p>Abbiamo ricevuto una richiesta di recupero password per il tuo account CoreSuite.</p>
            <p>Per reimpostare la tua password, clicca sul pulsante sottostante:</p>
            <p style="text-align: center;">
                <a href="<?php echo $resetLink; ?>" class="button">Reimposta Password</a>
            </p>
            <div class="warning">
                <p><strong>Nota:</strong> Questo link sarà valido per le prossime 24 ore.</p>
                <p>Se non hai richiesto il recupero della password, ignora questa email. La tua password non sarà modificata.</p>
            </div>
            <p>Cordiali saluti,<br>Il team di CoreSuite</p>
        </div>
        <div class="footer">
            <p>© <?php echo date('Y'); ?> CoreSuite IT - Tutti i diritti riservati.</p>
            <p>Ricevi questa email perché è stata richiesta una modifica della password per il tuo account.</p>
        </div>
    </div>
</body>
</html>
