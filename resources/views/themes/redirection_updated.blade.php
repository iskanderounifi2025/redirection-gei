<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mise à jour de la redirection</title>
</head>
<body>
    <h1>Mise à jour de la redirection</h1>
    <p>Bonjour,</p>
    <p>La redirection avec les informations suivantes a été mise à jour :</p>
    <ul>
        <li>Produit : {{ $redirection->nom_produit }}</li>
        <li>Prix : {{ $redirection->prix_produit }}</li>
        <li>Réduction : {{ $redirection->reduction_produit }}%</li>
        <li>Client : {{ $redirection->client_nom }} {{ $redirection->client_prenom }}</li>
        <li>Email du client : {{ $redirection->client_email }}</li>
        <li>Téléphone du client : {{ $redirection->client_phone }}</li>
    </ul>
    <p>Cordialement,</p>
    <p>L'équipe</p>
</body>
</html>
