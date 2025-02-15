<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Revendeurs</title>
    @include('themes.style')

    <!-- Inclure Morris.js CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/morris.js/morris.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Ajout de Chart.js -->

</head>

<body>
    <div class="container-scroller">
        @include('themes.header')
        @include('themes.sideleft')

        <!-- Affichage du chiffre d'affaire par événement -->
        <div class="main-panel">
            <div class="content-wrapper">

                <div class="row">
                    <div class="col-md-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Classement des Revendeurs par Chiffre d'Affaires</h3>

                                <!-- Graphique avec Morris.js -->
                                <div class="col-md-12 mb-3">
                                    <canvas id="revendeursChart" style="height: 250px;"></canvas>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <input type="text" id="searchInputProduit" class="form-control" placeholder="Rechercher un produit..." onkeyup="searchTable()">
                                </div>
                                <div class="table table-responsive">
                                    <table id="revendeursTable" class="table table-respensive">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Rang</th>
                                                <th>Revendeur</th>
                                                <th>Chiffre d'Affaires (Année Courante)</th>
                                                <th>Chiffre d'Affaires (Année Précédente)</th>
                                                <th>Augmentation (%)</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($revendeursData as $revendeur)
                                            @php
                                                // Calcul de l'augmentation en pourcentage
                                                $augmentationPourcentage = 0;
                                                if ($revendeur->chiffre_affaire_last_year > 0) {
                                                    $augmentationPourcentage = (($revendeur->chiffre_affaire_current_year - $revendeur->chiffre_affaire_last_year) / $revendeur->chiffre_affaire_last_year) * 100;
                                                }
                                            @endphp
                                            <tr>
                                                <td></td>
                                                <td>{{ $revendeur->rank }}</td>
                                                <td>{{ $revendeur->revendeur }}</td>
                                                <td>{{ number_format($revendeur->chiffre_affaire_current_year, 3) }} TND</td>
                                                <td>{{ number_format($revendeur->chiffre_affaire_last_year, 3) }} TND</td>
                                                <td>
                                                    @if($revendeur->chiffre_affaire_last_year > 0)
                                                        @if($augmentationPourcentage > 0)
                                                            <i class="fa fa-arrow-up" style="color: green;"></i> {{ number_format($augmentationPourcentage, 3) }} %
                                                        @elseif($augmentationPourcentage < 0)
                                                            <i class="fa fa-arrow-down" style="color: red;"></i> {{ number_format($augmentationPourcentage, 3) }} %
                                                        @else
                                                            <i class="fa fa-minus" style="color: gray;"></i> {{ number_format($augmentationPourcentage, 3) }} %
                                                        @endif
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center mt-4">
                                {{ $revendeursData->links('pagination::bootstrap-5') }}
                            </div>

                        </div>
                    </div>
                    <div class="col-md-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                            <h3 class="card-title">Chiffre d'Affaires par Marque et Revendeur ({{ $currentYear }})</h3>

                    <div class="class-respensive">
                    @foreach($resultatsByMarque as $marque => $revendeurs)
    <h3>Marque: {{ $marque }}</h3>
    <table class="table table-respensive" style="width:100%">
        <thead>
            <tr>
            <th>Rang</th>

                <th>Revendeur</th>
                <th>Chiffre d'Affaire</th>
            </tr>
        </thead>
        <tbody>
            @foreach($revendeurs as $revendeur)
                <tr>
                <td>{{ $revendeur->rank }}</td>

                    <td>{{ $revendeur->revendeur }}</td>
                    <td>{{ number_format($revendeur->chiffre_affaire, 2) }} TND</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endforeach


                    
                                            </div>
                                            </div>
                                            </div>
                                            </div>
                </div>
            </div>
        </div>

        <!-- Search Table Script -->
        <script>
            function searchTable() {
                const input = document.getElementById('searchInputProduit');
                const filter = input.value.toLowerCase();
                const rows = document.querySelectorAll('#revendeursTable tbody tr');

                rows.forEach((row) => {
                    const nameCell = row.cells[2]; // Vérifier la colonne 'Revendeur'
                    if (nameCell && nameCell.textContent.toLowerCase().includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
        </script>

        <script>
            // Récupération des données du tableau pour le graphique
            const revendeurs = @json($revendeursData->pluck('revendeur'));
            const ventesActuelles = @json($revendeursData->pluck('chiffre_affaire_current_year'));
            const ventesAnneePrecedente = @json($revendeursData->pluck('chiffre_affaire_last_year'));

            // Configuration du graphique
            const ctx = document.getElementById('revendeursChart').getContext('2d');
            const revendeursChart = new Chart(ctx, {
                type: 'line', // Changer le type de graphique à 'line' pour un graphique linéaire
                data: {
                    labels: revendeurs, // Noms des revendeurs sur l'axe X
                    datasets: [{
                        label: 'Chiffre d\'Affaires (Année Courante)',
                        data: ventesActuelles, // Données de l'année courante
                        fill: false, // Ne pas remplir sous la ligne
                        borderColor: '#4caf50', // Couleur de la ligne pour l'année courante
                        borderWidth: 2,
                        tension: 0.1 // Option pour lisser la courbe de la ligne
                    }, {
                        label: 'Chiffre d\'Affaires (Année Précédente)',
                        data: ventesAnneePrecedente, // Données de l'année précédente
                        fill: false, // Ne pas remplir sous la ligne
                        borderColor: '#2196f3', // Couleur de la ligne pour l'année précédente
                        borderWidth: 2,
                        tension: 0.1 // Option pour lisser la courbe de la ligne
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString() + ' TND'; // Format du chiffre d'affaires sur l'axe Y
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.raw.toLocaleString() + ' TND'; // Format du chiffre d'affaires dans les tooltips
                                }
                            }
                        }
                    }
                }
            });
        </script>

        @include('themes.js')
    </div>
</body>

</html>
