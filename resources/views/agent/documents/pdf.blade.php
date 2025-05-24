<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Document Approuvé</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .signature { margin-top: 30px; }
    </style>
</head>
<body>
    <h1>Document approuvé</h1>

    <p><strong>Numéro registre :</strong> {{ $document->registry_number }}</p>
    <p><strong>Page :</strong> {{ $document->registry_page }}</p>
    <p><strong>Volume :</strong> {{ $document->registry_volume }}</p>
    <p><strong>Date décision :</strong> {{ $document->decision_date }}</p>

    @if($document->agent && $document->agent->signature_path)
        <div class="signature">
            <p><strong>Signature de l’agent :</strong></p>
            <img src="{{ public_path($document->agent->signature_path) }}" width="150">
        </div>
    @endif
</body>
</html>
