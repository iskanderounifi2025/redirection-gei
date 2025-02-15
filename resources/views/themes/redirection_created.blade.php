<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GEI - Détails de la Redirection - {{ $redirection->reference }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
             padding: 20px;
        }
        h1, h2 {
            color: #343a40;
        }
        table {
            font-size: 0.9rem;
        }
        .badge {
            font-size: 0.8rem;
        }
        .footer-text {
            font-size: 0.85rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="text-center mb-4">
            <img src="http://127.0.0.1:8000/images/logo.svg" alt="GEI Logo" class="rounded float-left" width="30%"><br>
            <h5 class="text-center">Détails Complets de la Redirection</h5>
        </div>

        <p>
            Bonjour,<br><br>
            Veuillez trouver ci-dessous la liste des articles pour une commande en redirection. Nous vous prions de bien vouloir traiter les commandes au prix affiché et de nous fournir un retour sous 24 heures avec le statut de progression :
        </p>
        <div class="mb-4">
            <span class="badge bg-primary me-2">Commande expédiée</span>
            <span class="badge bg-success me-2">Commande livrée</span>
            <span class="badge bg-danger">Commande annulée</span>
        </div>

        <p><strong>Client :</strong> {{ $redirection->client_nom }} {{ $redirection->client_prenom }}</p>
        <p><strong>Email :</strong> {{ $redirection->client_email }}</p>
        <p><strong>Téléphone :</strong> {{ $redirection->client_phone }}</p>
        <p><strong>Adresse :</strong> {{ $redirection->client_adresse }}</p>
        <p><strong>Marque :</strong> {{ $redirection->brand->name ?? 'Non disponible' }}</p>

        <h2 class="mt-4">Détails des Produits</h2>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>SKU</th>
                    <th>Nom du Produit</th>
                    <th>Quantité</th>
                    <th>Prix Final TTC</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $sousTotal = 0;
                @endphp
                @foreach ($productDetails as $produit)
                    @php
                        $prixFinal = $produit->prix_produit * (1 - $produit->reduction_produit / 100);
                        $sousTotal += $prixFinal;
                    @endphp
                    <tr>
                        <td>{{ $produit->sku ?? 'Non disponible' }}</td>
                        <td>{{ $produit->name ?? 'Non disponible' }}</td>
                        <td>{{ $produit->qts_produit ?? 'Non disponible' }}</td>
                        <td>{{ number_format($prixFinal, 3) }} TND</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-secondary">
                    <td colspan="3" class="text-end"><strong>Sous-total</strong></td>
                    <td><strong>{{ number_format($sousTotal, 3) }} TND</strong></td>
                </tr>
                <tr class="table-secondary">
                    <td colspan="3" class="text-end"><strong>Timber Fiscal</strong></td>
                    <td><strong>{{ number_format($redirection->timber_fiscal, 3) }} TND</strong></td>
                </tr>
                <tr class="table-secondary">
                    <td colspan="3" class="text-end"><strong>Frais de Livraison</strong></td>
                    <td><strong>{{ number_format($redirection->frais_laivraison, 3) }} TND</strong></td>
                </tr>
                <tr class="table-dark">
                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                    @php
                        $total = $sousTotal + $redirection->timber_fiscal + $redirection->frais_laivraison;
                    @endphp
                    <td><strong>{{ number_format($total, 3) }} TND</strong></td>
                </tr>
            </tfoot>
        </table>

        <p class="mt-4">
            Merci de confirmer la réception de cette commande.<br>
            <strong>Cordialement / Best regards,</strong>
        </p>
        <p class="footer-text text-center mt-4">GEI - Your quality partner .</p>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
