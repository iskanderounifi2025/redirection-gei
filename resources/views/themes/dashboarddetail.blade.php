<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard detail</title>
    @include('themes.style')
</head>

<body>
    <div class="container-scroller">
        @include('themes.header')
        @include('themes.sideleft')

        <div class="main-panel">
            <div class="content-wrapper">
                <!-- Date Range Section -->
                <div class="row mb-4">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Statistiques en temps réel </h4>
                                <form id="dateForm">
                                    <label for="start_date">Date de début :</label>
                                    <input type="date" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="form-control mb-3">

                                    <label for="end_date">Date de fin :</label>
                                    <input type="date" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="form-control mb-3">

                                    <button type="submit" class="btn btn-primary">Rechercher</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Section -->
                <h3>Statistiques en temps réel</h3>

                <!-- Brands Ranking -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h4>Classement des Marques</h4>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nom de la marque</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="brandRanking">
                                @foreach($brandRanking as $brand)
                                    <tr>
                                        <td>{{ $brand->name }}</td>
                                        <td>{{ $brand->total }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Product Ranking -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h4>Classement des Produits</h4>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nom du produit</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="productRanking">
                                @foreach($productRanking as $product)
                                    <tr>
                                        <td>{{ $product->product }}</td>
                                        <td>{{ $product->total }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div> <!-- content-wrapper ends -->
        </div> <!-- main-panel ends -->
    </div> <!-- container-scroller ends -->

    @include('themes.js')

    <script>
        // Mettre à jour les statistiques en temps réel dès qu'une date est modifiée
        $('#start_date, #end_date').on('change', function() {
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            $.ajax({
                url: '{{ route("themes.dashboarddetail") }}',  // Route vers votre méthode contrôleur
                type: 'GET',
                data: {
                    start_date: startDate,
                    end_date: endDate
                },
                success: function(data) {
                    // Mettez à jour les données du tableau en fonction des nouvelles dates
                    $('#brandRanking').html(data.brandRanking);
                    $('#productRanking').html(data.productRanking);
                },
                error: function(xhr, status, error) {
                    console.error("Erreur de récupération des données :", error);
                }
            });
        });
    </script>
</body>

</html>
