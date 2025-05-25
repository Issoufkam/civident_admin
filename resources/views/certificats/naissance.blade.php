<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Acte de Naissance</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            position: relative;
            padding: 40px;
        }

        .background {
            position: absolute;
            top: 25%;
            left: 25%;
            width: 50%;
            opacity: 0.08;
            z-index: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            z-index: 1;
        }

        .left, .right {
            width: 48%; /* Ajusté pour que les deux tiennent sur la même ligne */
        }

        .left img {
            width: 80px;
            height: auto;
        }

        .right {
            text-align: right;
            font-weight: bold;
        }

        .content {
            margin-top: 60px;
            z-index: 1;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 80px;
        }

        .timbre {
            width: 100px;
            height: auto;
        }

        .signature-block {
            text-align: right;
        }

        .signature-block img {
            width: 150px;
            height: auto;
        }

        .date-block {
            text-align: right;
            /* margin-top: 20px; */
            font-style: italic;
        }
    </style>
</head>
<body>

    {{-- Filigrane --}}
    <img src="{{ public_path('images/armoirie.png') }}" class="background" alt="Armoirie de la Côte d'Ivoire">

    {{-- En-tête --}}
    <div class="header">
        <div class="left">
            <p><strong>Commune de {{ $document->commune->name }}</strong></p>
            <img src="{{ public_path('images/armoirie.png') }}" alt="Logo de la Côte d'Ivoire">
            <p><strong>ETAT CIVIL</strong></p>
        </div>

        <div class="right">
            <p>République de Côte d’Ivoire</p>
        </div>
    </div>

    {{-- Contenu principal --}}
    <div class="content">
        <h2 style="text-align: center; text-decoration: underline;">Extrait d'acte de naissance</h2>

        <p><strong>Nom de l’enfant :</strong> {{ $document->metadata['nom_enfant'] ?? 'Non renseigné' }}</p>
        <p><strong>Date de naissance :</strong> {{ \Carbon\Carbon::parse($document->metadata['date_naissance'] ?? null)->format('d/m/Y') ?? 'Non renseignée' }}</p>
        <p><strong>Lieu de naissance :</strong> {{ $document->metadata['lieu_naissance'] ?? 'Non renseigné' }}</p>
        <p><strong>Nom du père :</strong> {{ $document->metadata['nom_pere'] ?? 'Non renseigné' }}</p>
        <p><strong>Nom de la mère :</strong> {{ $document->metadata['nom_mere'] ?? 'Non renseigné' }}</p>

        {{-- Pied de page avec timbre et signature --}}
        <div class="footer">
            <div class="timbre-block">
                <img src="{{ $timbre }}" class="timbre" alt="Timbre officiel">
            </div>
            <div class="signature-block">
                <p>L’Officier d’état civil</p>
                <img src="{{ $signature }}" alt="Signature de l'agent">
                <p>{{ $document->agent?->nom ?? Auth::user()->nom }}</p>
            </div>
        </div>

        {{-- Date centrée --}}
        <div class="date-block">
            <p>Fait à {{ $document->commune->nom }}, le {{ \Carbon\Carbon::parse($document->traitement_date)->translatedFormat('d F Y') }}</p>
        </div>
    </div>

</body>
</html>
