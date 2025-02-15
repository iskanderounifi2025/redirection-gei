<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard des Redirections</title>
    @include('themes.style')
    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                            <!-- Section principale avec le contenu -->
                            <div class="card-body">
                                <h3 class="card-title">Dashboard des Redirections</h3>
                                <div class="row mb-4">
                                    <!-- Statistiques -->
                                    <div class="col-md-4">
                                        <div class="card bg-primary text-white">
                                            <div class="card-body">
                                            <div class="me-3">
                            <i class="ti-shopping-cart-full ti-3x"></i>
                        
             
</div>
                                                <h5>Total Commandes (TND)</h5>
                                                <h3>{{ number_format($totalRedirections, 2) }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-success text-white">
                                            <div class="card-body">
                                            <div class="me-3">
                            
        
                            <i class="ti-reload ti-3x"></i>
                                                    
                                                    </div
                            
                                    
                                                <h5>Total Redirections</h5>
                                                <h3>{{ $totalOrders }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-info text-white">
                                            <div class="card-body">
                                            <div class="me-3">
                            <i class="ti-bar-chart ti-3x"></i>
                        </div>
                                                <h5>Revenus Année (YTD)</h5>
                                                <h3>{{ number_format($revenueYTD, 2) }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <div class="card bg-warning text-white">
                                            <div class="card-body">
                                            <div class="me-3">
                            <i class="ti-calendar ti-3x"></i>
                        </div>
                                                <h5>Revenus Mois (MTD)</h5>
                                                <h3>{{ number_format($revenueMTD, 2) }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-danger text-white">
                                            <div class="card-body">
                                            <div class="me-3">
                            <i class="ti-time ti-3x"></i>
                        </div>
                                                <h5>Revenus Derniers 7 Jours</h5>
                                                <h3>{{ number_format($revenueLast7Days, 2) }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-secondary text-white">
                                            <div class="card-body">
                                            <div class="me-3">
                            <i class="ti-stats-up ti-3x"></i>
                        </div>
                                                <h5>Revenus Aujourd'hui</h5>
                                                <h3>{{ number_format($revenueToday, 2) }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Graphique -->
                                <div class="row">
                                    <div class="col-md-12 grid-margin">
                                        <div class="card">
                                            <h5>Graphique des Revenus des 30 derniers jours</h5>
                                            <canvas id="revenueChart" style="width: 100%; height: 400px;"></canvas>
                                            <!-- Bouton pour télécharger le graphique -->
                                            <button id="downloadBtn" class="btn btn-primary mt-3">Télécharger le graphique</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @include('themes.js')
    <script>
    // Données pour le graphique
    const revenueData = @json($revenueData);

    // Fonction pour regrouper les données par mois
    function groupByMonth(data) {
        const groupedData = {};

        data.forEach(entry => {
            // Récupérer l'année et le mois sous forme 'YYYY-MM'
            const month = entry.month; // Mois déjà formaté en 'YYYY-MM'
            
            if (!groupedData[month]) {
                groupedData[month] = 0;
            }

            // Additionner les revenus pour chaque mois
            groupedData[month] += entry.total;
        });

        // Convertir l'objet en tableau de labels et de valeurs
        const months = Object.keys(groupedData);
        const revenues = Object.values(groupedData);

        return { months, revenues };
    }

    // Regrouper les données par mois
    const { months, revenues } = groupByMonth(revenueData);

    // Fonction pour formater les mois en noms courts
    function formatMonth(month) {
        const monthNames = [
            "Jan", "Fév", "Mar", "Avr", "Mai", "Juin",
            "Juil", "Août", "Sep", "Oct", "Nov", "Déc"
        ];
        const monthIndex = parseInt(month.substring(5, 7)) - 1;
        return monthNames[monthIndex];
    }

    // Formater les mois en noms
    const formattedMonths = months.map(month => formatMonth(month));

    // Définir les couleurs pour chaque barre
    const colors = months.map((month, index) => {
        const currentMonth = new Date().toISOString().substring(0, 7); // Format 'YYYY-MM'
        return month === currentMonth ? 'rgba(255, 99, 132, 1)' : 'rgba(75, 192, 192, 1)';
    });

    // Configuration du graphique
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'bar', // Type du graphique
        data: {
            labels: formattedMonths, // Afficher les mois formatés
            datasets: [{
                label: 'Revenus (TND)',
                data: revenues,
                backgroundColor: colors,  // Application des couleurs
                borderColor: colors, // Utilisation des couleurs pour les bordures aussi
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Mois'
                    },
                    ticks: {
                        autoSkip: true,
                        maxTicksLimit: 12
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Revenus (TND)'
                    },
                    beginAtZero: true
                }
            }
        }
    });

    // Télécharger le graphique
    document.getElementById('downloadBtn').addEventListener('click', function() {
        // Télécharger l'image du graphique sous format PNG
        const imageUrl = revenueChart.toBase64Image();
        const link = document.createElement('a');
        link.href = imageUrl;
        link.download = 'graphique_revenus_mensuels.png';
        link.click();
    });
</script>

</body>
</html>
