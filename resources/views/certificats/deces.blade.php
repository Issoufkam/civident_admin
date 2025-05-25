<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Acte de Décès</title>
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
            position: relative;
        }

        .left {
            width: 40%;
        }

        .left img {
            width: 80px;
            height: auto;
        }

        .right {
            width: 40%;
            text-align: right;
            font-weight: bold;
        }

        .content {
            margin-top: 60px;
            position: relative;
            z-index: 1;
        }

        .title {
            text-align: center;
            text-decoration: underline;
            font-size: 20px;
            margin-bottom: 40px;
        }

        .signature-block {
            margin-top: 80px;
            text-align: right;
        }

        .signature-block img {
            width: 150px;
            height: auto;
            margin-top: 10px;
        }

        .timbre {
            position: absolute;
            top: 40px;
            right: 40px;
            width: 100px;
            height: auto;
            z-index: 2;
        }

        p {
            line-height: 1.6;
            font-size: 14px;
        }
    </style>
</head>
<body>

    {{-- Arrière-plan discret --}}
    <img src="{{ public_path('images/armoirie.png') }}" class="background" alt="Armoirie de la Côte d'Ivoire">

    {{-- Timbre officiel --}}
    <img src="{{ $timbre }}" class="timbre" alt="Timbre officiel">

    <div class="header">
        <div class="left">
            <p><strong>Commune de {{ $document->commune->nom }}</strong></p>
            <img src="{{ public_path('images/armoirie.png') }}" alt="Logo de la Côte d'Ivoire">
            <p><strong>ETAT CIVIL</strong></p>
        </div>

        <div class="right">
            <p>République de Côte d’Ivoire</p>
        </div>
    </div>

    <div class="content">
        <h2 class="title">Extrait d'acte de décès</h2>

        <p><strong>Nom du défunt :</strong> {{ $document->metadata['nom_defunt'] ?? '...' }}</p>
        <p><strong>Date du décès :</strong> {{ $document->metadata['date_deces'] ?? '...' }}</p>
        <p><strong>Lieu du décès :</strong> {{ $document->metadata['lieu_deces'] ?? '...' }}</p>
        <p><strong>Date de naissance :</strong> {{ $document->metadata['date_naissance'] ?? '...' }}</p>

        <p style="margin-top: 50px;">Fait à {{ $document->commune->nom }}, le {{ $document->traitement_date_formatted }}</p>

        <div class="signature-block">
            <p>L’Officier d’état civil</p>
            <img src="{{ $signature }}" alt="Signature de l'agent">
            <p>{{ $document->agent?->name ?? Auth::user()->name }}</p>
        </div>
    </div>

</body>
</html>
