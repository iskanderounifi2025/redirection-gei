<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chiffre d'Affaire par Marque et Événement</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-semibold mb-6">Chiffre d'Affaire et ROI par Marque et Événement</h1>
        <table>
    <thead>
        <tr>
            <th>Marque</th>
            <th>Événement</th>
            <th>Chiffre d'Affaire</th>
            <th>Coût Total</th>
            <th>Frais de Campagne</th>
            <th>ROI (%)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($resultats as $resultat)
            <tr>
                <td>{{ $resultat->marques }}</td>
                <td>{{ $resultat->evenement }}</td>
                <td>{{ number_format($resultat->chiffre_affaire, 2) }}</td>
                <td>{{ number_format($resultat->cout_total, 2) }}</td>
                <td>{{ number_format($resultat->frais_compagne, 2) }}</td>
                <td>{{ number_format($resultat->roi, 2) }}%</td>
            </tr>
        @endforeach
    </tbody>
</table>
    </div>

</body>
</html>
