<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Document Approuvé</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        h1 { color: #2e6da4; }
        .signature { margin-top: 30px; }
        .section { margin-bottom: 20px; }
        .label { font-weight: bold; }
    </style>
</head>
<body>
    <h1>Document approuvé</h1>

    <div class="section">
        <p><span class="label">Numéro registre :</span> {{ $document->registry_number }}</p>
        <p><span class="label">Page :</span> {{ $document->registry_page }}</p>
        <p><span class="label">Volume :</span> {{ $document->registry_volume }}</p>
        <p><span class="label">Date de décision :</span> {{ $document->decision_date }}</p>
    </div>

    <div class="section">
        <h2>Détails du document ({{ ucfirst($document->type->value) }})</h2>

        @php $data = $document->metadata; @endphp

        @if($document->type->value === 'naissance')
            <p><span class="label">Nom de l'enfant :</span> {{ $data['nom_enfant'] ?? '-' }}</p>
            <p><span class="label">Date de naissance :</span> {{ $data['date_naissance'] ?? '-' }}</p>
            <p><span class="label">Lieu de naissance :</span> {{ $data['lieu_naissance'] ?? '-' }}</p>
            <p><span class="label">Nom du père :</span> {{ $data['nom_pere'] ?? '-' }}</p>
            <p><span class="label">Nom de la mère :</span> {{ $data['nom_mere'] ?? '-' }}</p>

        @elseif($document->type->value === 'mariage')
            <p><span class="label">Nom époux :</span> {{ $data['nom_epoux'] ?? '-' }}</p>
            <p><span class="label">Nom épouse :</span> {{ $data['nom_epouse'] ?? '-' }}</p>
            <p><span class="label">Date du mariage :</span> {{ $data['date_mariage'] ?? '-' }}</p>
            <p><span class="label">Lieu du mariage :</span> {{ $data['lieu_mariage'] ?? '-' }}</p>

        @elseif($document->type->value === 'deces')
            <p><span class="label">Nom défunt :</span> {{ $data['nom_defunt'] ?? '-' }}</p>
            <p><span class="label">Date du décès :</span> {{ $data['date_deces'] ?? '-' }}</p>
            <p><span class="label">Lieu du décès :</span> {{ $data['lieu_deces'] ?? '-' }}</p>
        @endif
    </div>

    @if($document->agent && $document->agent->signature_path)
        <div class="signature">
            <p><span class="label">Signature de l’agent :</span></p>
            <img src="{{ public_path($document->agent->signature_path) }}" width="150">
        </div>
    @endif
</body>
</html>
