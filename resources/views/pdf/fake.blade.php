<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Document Fictif</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { text-align: center; }
        table { width: 100%; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Document Fictif</h1>
    <table>
        @foreach($metadata as $key => $value)
            <tr>
                <td><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}</strong></td>
                <td>{{ $value }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>
