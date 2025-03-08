<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promemoria Appuntamento</title>
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
        .appointment-details {
            margin: 20px 0;
            padding: 15px;
            background-color: #f0f7ff;
            border-left: 4px solid #0078d4;
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
            <h1>Promemoria Appuntamento</h1>
        </div>
        <div class="content">
            <p>Gentile <?php echo htmlspecialchars($cliente_nome . ' ' . $cliente_cognome); ?>,</p>
            <p>Ti ricordiamo che hai un appuntamento programmato con noi.</p>
            
            <div class="appointment-details">
                <h3>Dettagli dell'appuntamento:</h3>
                <p><strong>Titolo:</strong> <?php echo htmlspecialchars($titolo ?? 'N/D'); ?></p>
                <p><strong>Data:</strong> <?php echo htmlspecialchars($data ?? 'N/D'); ?></p>
                <p><strong>Ora:</strong> <?php echo htmlspecialchars($ora ?? 'N/D'); ?></p>
                <p><strong>Descrizione:</strong> <?php echo htmlspecialchars($descrizione ?? 'N/D'); ?></p>
            </div>
            
            <p>Ti aspettiamo puntuale! In caso di impossibilità a partecipare, ti preghiamo di avvisarci il prima possibile.</p>
            <p>Cordiali saluti,<br>Il team di CoreSuite</p>
        </div>
        <div class="footer">
            <p>© <?php echo date('Y'); ?> CoreSuite IT - Tutti i diritti riservati.</p>
            <p>Ricevi questa email come promemoria per il tuo appuntamento.</p>
        </div>
    </div>
</body>
</html>
