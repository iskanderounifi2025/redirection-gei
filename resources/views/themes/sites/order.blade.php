<!DOCTYPE html>
<html lang="fr-TN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes - {{ $site->name }}</title>
    @include('themes.style')
</head>

<body>
    <div class="container-scroller">
        @include('themes.header')
        @include('themes.sideleft')

        <div class="main-panel">
            <div class="content-wrapper">
                <div class="card">
                    <div class="card-body">
                        <h3>Commandes pour le site : {{ $site->name }}</h3>

                        @if(is_string($orders) && strpos($orders, 'Erreur :') === 0)
                            <p class="text-danger">{{ $orders }}</p>
                        @else
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Client</th>
                                        <th>Adresse</th>
                                        <th>Produits</th>
                                        <th>Mode de Paiement</th>
                                        <th>Mode de Livraison</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($order['date_created'])->format('d/m/Y') }}</td>
                                            <td>{{ $order['total'] }}</td>
                                            <td>{{ $order['billing']['first_name'] }} {{ $order['billing']['last_name'] }}</td>
                                            <td>
                                                {{ $order['billing']['address_1'] }},
                                                {{ $order['billing']['city'] }},
                                                {{ $order['billing']['country'] }}
                                            </td>
                                            <td>
                                                <ul>
                                                    @foreach($order['line_items'] as $item)
                                                        <li>{{ $item['name'] }} (Quantité : {{ $item['quantity'] }})</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>{{ $order['payment_method_title'] }}</td>
                                            <td>{{ $order['shipping_lines'][0]['method_title'] ?? 'Non défini' }}</td>
                                            <td>
                                                <button class="btn btn-success create-redirection-btn" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#redirectionModal" 
                                                        data-order-id="{{ $order['id'] }}" 
                                                        data-order-products="{{ json_encode($order['line_items']) }}">
                                                    Créer une redirection
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modale pour créer une redirection -->
    <div class="modal fade" id="redirectionModal" tabindex="-1" aria-labelledby="redirectionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="redirectionModalLabel">Créer une redirection</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('redirections.store') }}" method="POST">
                        @csrf
                        <input type="hidden" id="modal_order_id" name="order_id">

                        <!-- Sélectionner un Revendeur -->
                        <div class="mb-3">
                            <label for="revendeur" class="form-label">Sélectionner un revendeur</label>
                            <select class="form-control" name="revendeur_id" id="revendeur" required>
                                <option value="">Sélectionner un revendeur</option>
                                @foreach($revendeurs as $revendeur)
                                    <option value="{{ $revendeur->id }}">{{ $revendeur->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Produits -->
                        <div class="mb-3">
                            <label for="produits" class="form-label">Produits</label>
                            <select class="form-control" name="produits[]" id="produits" multiple required>
                                <!-- Les options seront remplies dynamiquement -->
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Créer la redirection</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('themes.js')

    <!-- JavaScript pour remplir le modal avec les données de la commande et récupérer les revendeurs -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const redirectionButtons = document.querySelectorAll('[data-bs-target="#redirectionModal"]');
            
            redirectionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const order = JSON.parse(this.getAttribute('data-order-products'));

                    // Remplir les champs du modal avec les données de la commande
                    document.getElementById('modal_order_id').value = this.getAttribute('data-order-id');

                    // Remplir le champ des produits avec les produits de la commande
                    const produitsSelect = document.getElementById('produits');
                    produitsSelect.innerHTML = '';  // Vider les options précédentes

                    // Boucle pour ajouter les produits dans la liste de sélection
                    order.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item['id'];  // Utiliser l'ID ou le SKU pour identifier chaque produit
                        option.textContent = `${item['name']} (Quantité : ${item['quantity']})`;
                        produitsSelect.appendChild(option);
                    });
                });
            });
        });
    </script>

</body>
</html>
