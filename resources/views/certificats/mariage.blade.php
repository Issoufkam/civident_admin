<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Acte de Mariage</title>
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

        .left {
            width: 40%;
        }

        .left img {
            width: 80px;
            height: auto;
        }

        .center {
            text-align: center;
            margin-top: 20px;
        }

        .right {
            width: 40%;
            text-align: right;
            font-weight: bold;
        }

        .content {
            margin-top: 60px;
            z-index: 1;
        }
    </style>
</head>
<body>

    <img src="{{ public_path('images/armoirie.png') }}" class="background" alt="Armoirie de la Côte d'Ivoire">

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
        <h2 style="text-align: center; text-decoration: underline;">Extrait d'acte de mariage</h2>

        <p><strong>Nom de l’époux :</strong> {{ $document->metadata['nom_epoux'] ?? '...' }}</p>
        <p><strong>Nom de l’épouse :</strong> {{ $document->metadata['nom_epouse'] ?? '...' }}</p>
        <p><strong>Date du mariage :</strong> {{ $document->metadata['date_mariage'] ?? '...' }}</p>
        <p><strong>Lieu du mariage :</strong> {{ $document->metadata['lieu_mariage'] ?? '...' }}</p>

        <p style="margin-top: 50px;">Fait à {{ $document->commune->nom }}, le {{ $document->traitement_date_formatted }}</p>

        <p style="text-align: right; margin-top: 80px;">L’Officier d’état civil</p>
    </div>

</body>
</html>
