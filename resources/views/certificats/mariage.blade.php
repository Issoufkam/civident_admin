<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extrait d'Acte de Naissance - République de Côte d'Ivoire</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Lato:wght@400;700&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Lato', sans-serif;
            background-color: #f5f5f5;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .certificate {
            background-color: #fff;
            width: 100%;
            max-width: 800px;
            padding: 40px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            border: 1px solid #ccc;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .certificate::before {
            content: '';
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            border: 2px solid #e0e0e0;
            pointer-events: none;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }

        .republic {
            font-size: 14px;
            text-transform: uppercase;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .motto {
            font-size: 12px;
            font-style: italic;
            margin-bottom: 15px;
        }

        .coat-of-arms {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Coat_of_arms_of_Ivory_Coast.svg/800px-Coat_of_arms_of_Ivory_Coast.svg.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }

        h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
            font-weight: 700;
        }

        .subtitle {
            font-size: 16px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .registry-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 15px;
        }

        .registry-info div {
            flex: 1;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .info-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .info-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 10px;
        }

        .info-field {
            flex: 1;
            min-width: 200px;
        }

        .info-label {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .info-value {
            padding: 5px 0;
            border-bottom: 1px dotted #ccc;
            min-height: 30px;
        }

        .official-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .stamp {
            width: 120px;
            height: 120px;
            border: 1px dashed #999;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #999;
            text-align: center;
            transform: rotate(-15deg);
            margin-left: auto;
        }

        .signature {
            align-self: flex-end;
            text-align: center;
            margin-bottom: 30px;
        }

        .signature-line {
            width: 200px;
            border-bottom: 1px solid #333;
            margin: 50px 0 10px;
        }

        .official-title {
            font-size: 14px;
            font-weight: bold;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 100px;
            opacity: 0.03;
            pointer-events: none;
            font-weight: bold;
            white-space: nowrap;
            color: #000;
        }

        .document-number {
            position: absolute;
            top: 20px;
            right: 40px;
            font-size: 14px;
            font-weight: bold;
        }

        .flag-colors {
            display: flex;
            height: 10px;
            width: 100%;
            margin-bottom: 20px;
        }

        .flag-colors div {
            flex: 1;
            height: 100%;
        }

        .color-orange { background-color: #F77F00; }
        .color-white { background-color: #FFFFFF; }
        .color-green { background-color: #009A44; }

        @media (max-width: 600px) {
            .certificate {
                padding: 20px;
            }

            h1 {
                font-size: 20px;
            }

            .info-field {
                min-width: 100%;
            }

            .registry-info {
                flex-direction: column;
                gap: 15px;
            }

            .official-section {
                flex-direction: column-reverse;
                align-items: center;
                gap: 30px;
            }

            .stamp {
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>
    <div class="certificate">
      <div class="document-number">N° 00-0000/2023</div>
        <div class="watermark">CÔTE D'IVOIRE</div>

        <div class="header">
            <div class="republic">RÉPUBLIQUE DE CÔTE D'IVOIRE</div>
            <div class="motto">UNION - DISCIPLINE - TRAVAIL</div>
            <div class="flag-colors">
                <div class="color-orange"></div>
                <div class="color-white"></div>
                <div class="color-green"></div>
            </div>
            <div class="coat-of-arms"></div>
            <h1>Extrait du Registre des Actes de l'État Civil</h1>
            <div class="subtitle">Acte de Naissance</div>
        </div>

        <div class="registry-info">
            <div>
                <div class="section-title">CENTRE D'ÉTAT CIVIL</div>
                <div>Commune d'Abidjan-Plateau</div>
            </div>
            <div>
                <div class="section-title">ANNÉE</div>
                <div>2023</div>
            </div>
            <div>
                <div class="section-title">REGISTRE</div>
                <div>Naissances</div>
            </div>
            <div>
                <div class="section-title">NUMÉRO</div>
                <div>0246/2023</div>
            </div>
        </div>

        <div class="info-container">
            <div class="info-row">
                <div class="info-field">
                    <div class="info-label">NOM</div>
                    <div class="info-value">KOUASSI</div>
                </div>
                <div class="info-field">
                    <div class="info-label">PRÉNOMS</div>
                    <div class="info-value">Aya Marie</div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-field">
                    <div class="info-label">SEXE</div>
                    <div class="info-value">Féminin</div>
                </div>
                <div class="info-field">
                    <div class="info-label">DATE DE NAISSANCE</div>
                    <div class="info-value">15 Avril 2023</div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-field">
                    <div class="info-label">HEURE DE NAISSANCE</div>
                    <div class="info-value">08h45</div>
                </div>
                <div class="info-field">
                    <div class="info-label">LIEU DE NAISSANCE</div>
                    <div class="info-value">CHU de Cocody, Abidjan</div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-field">
                    <div class="info-label">PÈRE - NOM ET PRÉNOMS</div>
                    <div class="info-value">KOUASSI Koffi Jean</div>
                </div>
                <div class="info-field">
                    <div class="info-label">PROFESSION DU PÈRE</div>
                    <div class="info-value">Ingénieur</div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-field">
                    <div class="info-label">MÈRE - NOM ET PRÉNOMS</div>
                    <div class="info-value">DIALLO Aminata</div>
                </div>
                <div class="info-field">
                    <div class="info-label">PROFESSION DE LA MÈRE</div>
                    <div class="info-value">Enseignante</div>
                </div>
            </div>
        </div>

        <div class="official-section">
            <div class="signature">
                <div class="signature-line"></div>
                <div class="official-title">L'Officier de l'État Civil</div>
            </div>

            <div class="stamp">
                Timbre Officiel
            </div>
        </div>

        <div style="margin-top: 40px; font-size: 12px; text-align: center;">
            Extrait conforme aux registres de l'état civil
            <br>
            Délivré à Abidjan, le 30 Avril 2023
        </div>
    </div>
</body>
</html>
