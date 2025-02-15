<!DOCTYPE html>
<html>
<head>
    <title>Récapitulatif des Redirections</title>
</head>
<body>
    <h2>Récapitulatif des redirections du {{ \Carbon\Carbon::now()->format('d-m-Y') }}</h2>
    <table border="1" cellspacing="0" cellpadding="10">
        <thead>
            <tr>
                <th>Revendeur</th>
                <th>Marque</th>
                <th>Client</th>
                <th>Téléphone</th>
                <th>Commande</th>
                <th>Référence</th>
                <th>Chiffre d'Affaire</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($resultats as $resultat)
                <tr>
                    <td>{{ $resultat->revendeur }}</td>
                    <td>{{ $resultat->marque }}</td>
                    <td>{{ $resultat->client }}</td>
                    <td>{{ $resultat->telephone }}</td>
                    <td>{{ $resultat->commande }}</td>
                    <td>{{ $resultat->ref }}</td>
                    <td>{{ number_format($resultat->chiffre_affaire, 2) }} TND</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
