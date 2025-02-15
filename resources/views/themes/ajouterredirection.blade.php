<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Ajouter nouvelle Demande de Redirection</title>
    @include('themes.style')
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container-scroller">
        @include('themes.header')
        @include('themes.sideleft')

        <div class="main-panel">
            
        <div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Ajouter nouvelle Demande de Redirection</h2> 
                <div class="d-flex justify-content-md-end">
                    <div>
                        <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" id="dropdownMenuDate2" aria-haspopup="true" aria-expanded="true">
                            <i class="mdi mdi-calendar"></i> Aujourd'hui ({{ now()->format('d M Y') }} - {{ now()->format('H:i') }})
                        </button>
                    </div>
                </div>
            </div>

            <!-- Messages de succès et d'erreur -->
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card-body">
                <form id="invoiceForm" method="POST" action="{{ route('redirections.store') }}">
                    @csrf
                    
                    <!-- Sélection de la marque -->
                    <div class="form-group">
                        <label for="brandSelect">Marque:</label>
                        <select name="brand_id" id="brandSelect" class="form-control" required>
                            <option value="">Choisir une marque</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sélection des produits -->
                    <div class="form-group">
                        <label for="productSelect">Produits (SKU):</label>
                        <select name="product_id[]" id="productSelect" class="form-control" multiple required>
                            <!-- Les produits seront chargés dynamiquement -->
                        </select>
                    </div>

                    <!-- Table de l'Invoice -->
                    <h3>Facture</h3>
                    <table class="table table-bordered" id="invoiceTable">
                        <thead>
                            <tr>
                                <th>Nom du produit</th>
                                <th>Prix</th>
                                <th>Quantité</th>
                                <th>Remise (%)</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Les lignes de produits seront ajoutées ici -->
                        </tbody>
                    </table>

                    <!-- Boutons d'action -->
                    <h3>Détails du Client</h3>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="clientNom">Nom:</label>
                            <input type="text" name="client_nom" id="clientNom" class="form-control" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="clientPrenom">Prénom:</label>
                            <input type="text" name="client_prenom" id="clientPrenom" class="form-control" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="clientEmail">Email:</label>
                            <input type="email" name="client_email" id="clientEmail" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="clientPhone">Téléphone:</label>
                            <input type="text" name="client_phone" id="clientPhone" class="form-control" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="clientAdresse">Adresse:</label>
                            <input type="text" name="client_adresse" id="clientAdresse" class="form-control" required>
                        </div>

                        <!-- Sélection du revendeur -->
                        <div class="form-group col-md-4">
                            <label for="resellerName">Nom du Revendeur:</label>
                            <select name="revendeur_id" id="resellerName" class="form-control" required>
                                <option value="">Choisir un revendeur</option>
                                @foreach($revendeurs as $revendeur)
                                    <option value="{{ $revendeur->id }}">{{ $revendeur->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="sexe">Sexe :</label>
                            <select name="sexe" id="sexe" class="form-control" required>
                                <option value="">Sexe</option>
                                <option value="H">Homme</option>
                                <option value="F">Femme</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="date_naissance_client">Date de Naissance du Client :</label>
                            <input type="date" name="date_naissance_client" class="form-control">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="evenements_id">Choisir un Événement :</label>
                            <select name="evenements_id" id="evenements_id" class="form-control" required>
                                <option value="">Choisir un Événement</option>
                                <option value="0">Aucun</option>
                                @foreach($evenements as $evenement)
                                    <option value="{{ $evenement->id }}">{{ $evenement->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="source_red">Choisir Source de Redirection :</label>
                            <select name="source_red" id="source_red" class="form-control" required>
                                <option value="">Choisir Source de Redirection</option>
                                <option value="Messenger">Messenger</option>
                                <option value="Siteweb">Siteweb</option>
                                <option value="Instagram">Instagram</option>
                                <option value="Télephone">Télephone</option>
                                <option value="Showroom">Showroom</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="frais_laivraison">Frais de Laivraison :</label>
                            <input type="number" name="frais_laivraison" placeholder="Frais de Laivraison ...." class="form-control">

                        </div>
                        <div class="form-group col-md-2">
                            <label for="timber_fiscal">Timber Fiscale :</label>
                            <select name="timber_fiscal" id="source_red" class="form-control" required>
                                <option value="">Choisir </option>
                                <option value="1">Oui</option>
                                <option value="0">Non</option>
                                 
                            </select>
                        </div>
                        <div class="col-mb-6">
<input type="hidden" name="id_team" value="{{ $team->id }}">
</div>
                    </div>

                    <button type="submit" class="btn btn-success" id="submitButton">Générer la Facture</button>
                    <button type="button" id="previewButton" class="btn btn-secondary">Aperçu de la Facture</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Scripts jQuery et Bootstrap -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
$(document).ready(function() {
    // Charger les produits dynamiquement en fonction de la marque sélectionnée
    $('#brandSelect').change(function() {
        var brandId = $(this).val();
        if (!brandId) return;

        $.ajax({
            url: '{{ route('getProductsByBrand') }}',
            type: 'GET',
            data: { brand_id: brandId },
            success: function(data) {
                $('#productSelect').empty();
                data.products.forEach(function(product) {
                    $('#productSelect').append('<option value="' + product.id + '" data-name="' + product.name + '" data-price="' + product.price + '">' + product.sku + '-' + product.name + ' - ' + product.price + '</option>');
                });
            }
        });
    });

    // Ajouter des produits sélectionnés à la table de la facture
    $('#productSelect').change(function() {
    $('#invoiceTable tbody').empty();

    $(this).find('option:selected').each(function() {
        var productId = $(this).val();
        var productName = $(this).data('name');
        var productPrice = parseFloat($(this).data('price'));

        $('#invoiceTable tbody').append(`
            <tr data-id="${productId}">
                <td><input type="text" class="form-control" name="nom_produit[]" value="${productName}" readonly></td>
                <td><input type="text" class="form-control price"  value="${productPrice}" name="prix_intial[]" readonly /></td>
                <td><input type="number" class="form-control quantity" name="qts_produit[]" value="1" min="1" /></td>
                <td><input type="text" class="form-control discount" name="reduction_produit[]" value="0" min="0" max="100" /></td>
                <td><input type="text" class="form-control total" name="prix_produit[]" value="0.00" readonly /></td>
                <td><button type="button" class="btn remove-product"><i class="fa fa-trash menu-icon"></i></button></td>
            </tr>
        `);
        updateRowTotal($('#invoiceTable tbody tr').last());
        updateTotal();
    });
});

    // Calculer les totaux en fonction des quantités et remises
    $('#invoiceTable').on('input', '.quantity, .discount', function() {
        var row = $(this).closest('tr');
        updateRowTotal(row);
        updateTotal();
    });

    // Supprimer une ligne de produit
    $('#invoiceTable').on('click', '.remove-product', function() {
        $(this).closest('tr').remove();
        updateTotal();
    });

    // Calculer le total par ligne
    function updateRowTotal(row) {
        var price = parseFloat(row.find('.price').val()) || 0;
        var quantity = parseInt(row.find('.quantity').val()) || 1;
        var discount = parseFloat(row.find('.discount').val()) || 0;

        // Calculer le total en tenant compte de la remise
        var total = price * quantity * (1 - discount / 100);

        // Mettre à jour la valeur de l'input total sans 'TND'
        row.find('.total').val(total.toFixed(2));
    }

    // Calculer le total global
    function updateTotal() {
        var total = 0;
        $('#invoiceTable tbody tr').each(function() {
            var rowTotal = parseFloat($(this).find('.total').val()) || 0;
            total += rowTotal;
        });
        // Afficher le total global si besoin
        console.log('Total global:', total.toFixed(2) + ' TND');
    }
});

    </script>

<script>
    $(document).ready(function() {
    // Remplir la modale avec les informations lorsque l'utilisateur clique sur "Aperçu de la Facture"
    $('#previewButton').click(function() {
        // Récupérer les valeurs saisies dans le formulaire
        var brandName = $('#brandSelect option:selected').text();
        var products = [];
        $('#productSelect option:selected').each(function() {
            var productName = $(this).text();
            var productPrice = $(this).data('price');
            products.push(productName + ' - ' + productPrice + ' TND');
        });

        var clientNom = $('#clientNom').val();
        var clientEmail = $('#clientEmail').val();
        var clientPhone = $('#clientPhone').val();
        var clientAdresse = $('#clientAdresse').val();
        
        var resellerName = $('#resellerName option:selected').text();
        
        var sexe = $('#resellerName option:selected').val();
        var dateNaissance = $('#date_naissance_client').val();
        var evenement = $('#evenements_id option:selected').text();
        var sourceRed = $('#source_red option:selected').text();

        // Mettre à jour les champs dans la modale
        $('#modalBrand').text(brandName);
        $('#modalProductList').empty();
        products.forEach(function(product) {
            $('#modalProductList').append('<li>' + product + '</li>');
        });

        $('#modalClientNom').text(clientNom);
        $('#modalClientEmail').text(clientEmail);
        $('#modalClientPhone').text(clientPhone);
        $('#modalClientAdresse').text(clientAdresse);

        $('#modalReseller').text(resellerName);
        $('#modalSexe').text(sexe);
        $('#modalDateNaissance').text(dateNaissance);
        $('#modalEvenement').text(evenement);
        $('#modalSourceRed').text(sourceRed);

        // Afficher la modale
        $('#previewModal').modal('show');
    });
});

    </script>
<!-- Modale d'aperçu de la facture -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="previewModalLabel">Aperçu de la Redirection</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4>Informations de la Redirection:</h4>
        <p><strong>Marque:</strong> <span id="modalBrand"></span></p>
        <p><strong>Produits:</strong></p>
        <ul id="modalProductList"></ul>
        
        <h4>Détails du Client:</h4>
        <p><strong>Nom:</strong> <span id="modalClientNom"></span></p>
        <p><strong>Email:</strong> <span id="modalClientEmail"></span></p>
        <p><strong>Téléphone:</strong> <span id="modalClientPhone"></span></p>
        <p><strong>Adresse:</strong> <span id="modalClientAdresse"></span></p>
        
        <h4>Revendeur:</h4>
        <p><strong>Nom du Revendeur:</strong> <span id="modalReseller"></span></p>

        <h4>Autres Détails:</h4>
        <p><strong>Sexe:</strong> <span id="modalSexe"></span></p>
        <p><strong>Date de Naissance:</strong> <span id="modalDateNaissance"></span></p>
        <p><strong>Événement:</strong> <span id="modalEvenement"></span></p>
        <p><strong>Source de Redirection:</strong> <span id="modalSourceRed"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>


</body>
</html>
