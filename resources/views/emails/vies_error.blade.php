<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VIES Connection Error</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif; color: #111827; }
        .container { max-width: 640px; margin: 24px auto; padding: 24px; border: 1px solid #e5e7eb; border-radius: 8px; }
        h1 { font-size: 20px; margin: 0 0 12px 0; }
        p { margin: 8px 0; }
        .muted { color: #6b7280; font-size: 12px; }
        .code { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace; background: #f3f4f6; padding: 2px 6px; border-radius: 4px; }
    </style>
    </head>
<body>
<div class="container">
    <h1>VAT Checker - VIES connection error</h1>
    <p>Si è verificato un errore di connessione al servizio VIES.</p>
    <p><strong>VAT:</strong> <span class="code">{{ $vatNumber }}</span></p>
    <p><strong>Errore:</strong> {{ $errorMessage }}</p>
    <p class="muted">Questa email è stata generata automaticamente dal pacchetto Laravel VAT Checker.</p>
    </div>
</body>
</html>


