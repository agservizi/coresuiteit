<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuovo Contratto Registrato</title>
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
        .contract-details {
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
            <h1>Nuovo Contratto Registrato</h1>
        </div>
        <div class="content">
            <p>Gentile Cliente,</p>
            <p>Ti confermiamo che il tuo contratto è stato registrato con successo nel nostro sistema.</p>
            
            <div class="contract-details">
                <h3>Dettagli del contratto:</h3>
                <p><strong>Tipologia:</strong> <?php echo $tipologia ?? 'N/D'; ?></p>
                <p><strong>Fornitore:</strong> <?php echo $fornitore ?? 'N/D'; ?></p>
                <?php if (isset($operatore)): ?>
                <p><strong>Operatore:</strong> <?php echo $operatore; ?></p>
                <?php endif; ?>
                <?php if (isset($piano_tariffario)): ?>
                <p><strong>Piano Tariffario:</strong> <?php echo $piano_tariffario; ?></p>
                <?php endif; ?>
                <?php if (isset($offerta)): ?>
                <p><strong>Offerta:</strong> <?php echo $offerta; ?></p>
                <?php endif; ?>
                <?php if (isset($data_attivazione)): ?>
                <p><strong>Data Attivazione:</strong> <?php echo $data_attivazione; ?></p>
                <?php endif; ?>
                <?php if (isset($referente)): ?>
                <p><strong>Referente:</strong> <?php echo $referente; ?></p>
                <?php endif; ?>
            </div>
            
            <p>Puoi visualizzare tutti i dettagli del contratto accedendo alla tua area personale.</p>
            <p>Per qualsiasi informazione o chiarimento, non esitare a contattarci.</p>
            <p>Cordiali saluti,<br>Il team di CoreSuite</p>
        </div>
        <div class="footer">
            <p>© <?php echo date('Y'); ?> CoreSuite IT - Tutti i diritti riservati.</p>
            <p>Ricevi questa email perché sei registrato sulla piattaforma CoreSuite.</p>
        </div>
    </div>
</body>
</html>
