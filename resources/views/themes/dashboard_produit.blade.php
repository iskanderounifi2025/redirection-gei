<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Produit</title>
    @include('themes.style')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container-scroller">
        @include('themes.header')
        @include('themes.sideleft')

        <div class="main-panel">
            <div class="content-wrapper">
                
                <!-- Tableau des produits avec leurs informations -->
                <div class="row">
                    <div class="col-md-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Graphique des Ventes par Produit</h3>
                                <!-- Canvas pour le graphique -->
                                <canvas id="salesChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Tableau de Bord - Produit</h3>

                                <!-- Recherche par produit -->
                                <div class="col-md-12 mb-3">
                                    <input type="text" id="searchInputProduit" class="form-control" placeholder="Rechercher un produit...">
                                </div>

                                <!-- Table des produits -->
                                <div class="table-responsive">
                                    <table id="produitTable" class="table table-respensive">
                                        <thead>
                                            <tr>
                                                <th>Rang</th>
                                                <th>SKU</th>
                                                <th>Chiffre d'Affaires {{ $lastYear }}</th>
                                                <th>Chiffre d'Affaires {{ $currentYear }}</th>
                                                <th>Augmentation (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($currentYearData as $index => $data)
                                                @php
                                                    // Calcul de l'augmentation en pourcentage pour chaque produit
                                                    $augmentationPourcentage = 0;
                                                    $lastYearChiffreAffaires = $lastYearData->where('sku', $data->sku)->first()->total_chiffre_affaires ?? 0;
                                                    if ($lastYearChiffreAffaires > 0) {
                                                        $augmentationPourcentage = (($data->total_chiffre_affaires - $lastYearChiffreAffaires) / $lastYearChiffreAffaires) * 100;
                                                    }
                                                @endphp
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $data->sku }}</td>
                                                    <td>{{ number_format($lastYearChiffreAffaires, 3, ',', ' ') }} TND</td>
                                                    <td>{{ number_format($data->total_chiffre_affaires, 3, ',', ' ') }} TND</td>
                                                    <td>
                                                        @if($augmentationPourcentage > 0)
                                                            <i class="fa fa-arrow-up" style="color: green;"></i> {{ number_format($augmentationPourcentage, 2, ',', ' ') }}%
                                                        @elseif($augmentationPourcentage < 0)
                                                            <i class="fa fa-arrow-down" style="color: red;"></i> {{ number_format($augmentationPourcentage, 2, ',', ' ') }}% 
                                                        @else
                                                            <i class="fa fa-minus" style="color: gray;"></i> 0%
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Pagination pour les produits -->
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $currentYearData->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SKU Moins Vendu -->
                <div class="row">
                    <div class="col-md-6 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">SKU les Moins Vendus</h3>
                                <!-- Recherche SKU Moins Vendu -->
                                <div class="col-md-12 mb-3">
                                    <input type="text" id="searchInputMoinsVendu" class="form-control" placeholder="Rechercher un SKU...">
                                </div>
                                <div class="table-responsive">
                                    <table id="leastSoldSKU" class="table table-respensive">
                                        <thead>
                                            <tr>
                                                <th>SKU</th>
                                                <th>Chiffre d'Affaires</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($bottomSkus as $bottom)
                                                <tr>
                                                    <td>{{ $bottom->sku }}</td>
                                                    <td>{{ number_format($bottom->total_chiffre_affaires, 2) }} TND</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Pagination pour les SKU Moins Vendu -->
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $bottomSkus->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SKU Plus Vendu -->
                    <div class="col-md-6 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">SKU les Plus Vendus</h3>

                                <!-- Recherche SKU Plus Vendu -->
                                <div class="col-md-12 mb-3">
                                    <input type="text" id="searchInputPlusVendu" class="form-control" placeholder="Rechercher un SKU...">
                                </div>
                                <div class="table-responsive">
                                    <table id="mostSoldSKU" class="table table-respensive">
                                        <thead>
                                            <tr>
                                                <th>SKU</th>
                                                <th>Chiffre d'Affaires</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($topSkus as $top)
                                                <tr>
                                                    <td>{{ $top->sku }}</td>
                                                    <td>{{ number_format($top->total_chiffre_affaires, 2) }} TND</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Pagination pour les SKU Plus Vendu -->
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $topSkus->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Données pour le graphique
            const produits = @json($currentYearData->pluck('sku'));
            const ventesActuelles = @json($currentYearData->pluck('total_chiffre_affaires'));
            const ventesAnneePrecedente = @json($lastYearData->pluck('total_chiffre_affaires'));

            // Configuration du graphique
            const ctx = document.getElementById('salesChart').getContext('2d');
            const salesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: produits, // Noms des produits sur l'axe X
                    datasets: [{
                        label: 'Chiffre d\'Affaires (Année Courante)',
                        data: ventesActuelles, // Données de l'année courante
                        backgroundColor: '#4caf50', // Couleur des barres de l'année courante
                        borderColor: '#388e3c', // Couleur de la bordure
                        borderWidth: 1
                    }, {
                        label: 'Chiffre d\'Affaires (Année Précédente)',
                        data: ventesAnneePrecedente, // Données de l'année précédente
                        backgroundColor: '#2196f3', // Couleur des barres de l'année précédente
                        borderColor: '#1976d2', // Couleur de la bordure
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.raw.toLocaleString() + ' TND'; // Format du chiffre d'affaires
                                }
                            }
                        }
                    }
                }
            });
        </script>

        <script>
            function searchTable(inputId, tableId) {
                const input = document.getElementById(inputId);
                const filter = input.value.toLowerCase();
                const table = document.getElementById(tableId);
                const rows = table.querySelectorAll('tbody tr');

                rows.forEach((row) => {
                    const cells = row.getElementsByTagName('td');
                    let match = false;

                    // Loop through all cells in the row (excluding the first column)
                    for (let i = 0; i < cells.length; i++) {
                        const cell = cells[i];
                        if (cell && cell.textContent.toLowerCase().includes(filter)) {
                            match = true;
                            break;
                        }
                    }

                    row.style.display = match ? '' : 'none';
                });
            }

            // Event listeners for search inputs
            document.getElementById('searchInputProduit').addEventListener('input', function() {
                searchTable('searchInputProduit', 'produitTable');
            });
            document.getElementById('searchInputMoinsVendu').addEventListener('input', function() {
                searchTable('searchInputMoinsVendu', 'leastSoldSKU');
            });
            document.getElementById('searchInputPlusVendu').addEventListener('input', function() {
                searchTable('searchInputPlusVendu', 'mostSoldSKU');
            });
        </script>

        @include('themes.js')
    </div>
</body>
</html>
