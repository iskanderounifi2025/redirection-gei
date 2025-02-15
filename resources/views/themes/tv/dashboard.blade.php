<!DOCTYPE html>
<html lang="fr">

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard TV</title>
  <!-- Inclure Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Inclure FontAwesome pour l'icône de la cloche -->
 
    
    <style>
        body {
            background-color: #333333;
            color: #fff;
            font-family: 'Roboto', sans-serif;
        }

        .sidebar {
            background-color: #1e1e2d;
            height: 100vh;
        }

        .card-body {
            background-color: #212529;

             border: none;
            border-radius: 15px;
            color: #FFFFFF;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 500;
            color: #fff;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .chart-container {
            height: 300px;
            width: 100%;
        }

        .table-responsive {
             overflow-x: auto;
            margin-top: 20px;
        }

        .btn-custom {
            background-color: #28a745;
            color: white;
        }

        /* Responsiveness for charts */
        @media (max-width: 768px) {
            .chart-container {
                height: 200px;
            }
        }

        /* Animation for cards */
        .card-body {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        /* Button hover effect */
        .btn-custom:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

    </style>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Fonction pour effectuer l'actualisation du contenu du body sans recharger la page entière
        function autoRefreshBody() {
            setInterval(function() {
                // Exemple d'Ajax pour récupérer tout le contenu de la page
                $.ajax({
                    url: 'votre-url-pour-recharger-le-body', // URL qui renvoie le contenu complet de la page
                    type: 'GET',
                    success: function(data) {
                        // Remplacer tout le contenu du body avec les nouvelles données
                        $('body').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error("Erreur lors de l'actualisation du contenu :", error);
                    }
                });
            }, 5000); // 600000 ms = 10 minutes
        }

        // Appeler la fonction dès le chargement de la page
        $(document).ready(function() {
            autoRefreshBody();
        });
    </script>
    <!-- Inclure les styles personnalisés -->
    @include('./themes.style')

    <!-- Charger ECharts -->
    <script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
</head>

<body>
    <div class="container-scroller">
        <!-- Header -->
        @include('themes.header')

        <!-- Sidebar -->
        @include('themes.sideleft')

        <!-- Main Content -->
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row mb-4">
                    <div class="col-12 col-md-8">
                        <!-- Placeholder for future content -->
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="d-flex justify-content-md-end">
                            <button class="btn btn-sm btn-dark bg-dark dropdown-toggle" type="button" id="dropdownMenuDate2" aria-haspopup="true" aria-expanded="true">
                                <i class="mdi mdi-calendar"></i> Aujourd'hui ({{ now()->format('d M Y') }} - {{ now()->format('H:i') }})
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Statistiques sous forme de cartes -->
                <div class="row">


                <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                        <div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3">
    @foreach($notifications as $notification)
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="fas fa-bell me-2"></i>
                <strong class="me-auto">
                    @if($notification->etat_red == 0)
                        Redirection Non Validée
                    @elseif($notification->etat_red == 2)
                        Redirection Validée
                    @endif
                </strong>
                <small class="text-muted">Juste maintenant</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Rédirection de Réf: {{ $notification->reference }} - {{ $notification->notification_count }} N'est Pas Validée.

            </div>
            <div class="toast-body">
            <a href="{{ url('listeredirection') }}" class="btn btn-primary btn-sm mt-2" target="_blanks">Valider</a>

    </div>
        </div>
    @endforeach
</div>


<!-- YouTube Player pour l'effet sonore (hidden) -->
<div id="youtube-audio" style="display: none;">
    <iframe width="1" height="1" id="audio-player" src="https://www.youtube.com/embed/8yYn75rs-J8?autoplay=1&mute=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
</div>

<!-- Effet sonore de notification et vibration -->
<script>
    // Fonction pour jouer le son de notification et appliquer la vibration
    function playNotificationEffect() {
        // Jouer le son de notification depuis YouTube
        const youtubePlayer = document.getElementById('audio-player');
        youtubePlayer.src = "https://www.youtube.com/embed/8yYn75rs-J8?autoplay=1&mute=0";  // Son en lecture

        // Vérifier si l'API de vibration est supportée par le navigateur
        if ("vibrate" in navigator) {
            // Vibrer pendant 500ms
            navigator.vibrate(500);
        }
    }

    // Appeler la fonction de notification et vibration lorsque des notifications sont présentes
    @if(count($notifications) > 0)
        playNotificationEffect();
    @endif
</script>

<!-- Style optionnel pour personnaliser l'apparence -->
<style>
    .toast-container {
        z-index: 1050; /* Pour s'assurer que les toasts sont au-dessus des autres éléments */
    }

    .toast {
        border-radius: 0.375rem;
    }
</style>

                          
                        </div>
                    </div>











                    <div class="col-md-4 grid-margin stretch-card">
                        <div class="card-body">
                            <div class="card-header">
                                Ventes Totales (Semaine en cours)
                            </div>
                            <div class="card-body">
                                <p class="fs-4">{{ number_format($chiffreAffaireCurrentWeek, 2) }} TND</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 grid-margin stretch-card">
                        <div class="card-body">
                            <div class="card-title">
                                Total Redirections (Semaine en cours)
                            </div>
                            <div class="card-body">
                                <p class="fs-4">{{ $totalRedirectionsCurrentWeek }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 grid-margin stretch-card">
                        <div class="card-body">
                            <div class="card-title">
                                Augmentation de la Redirection
                            </div>
                            <div class="card-body">
                                <p class="fs-4">{{ $augmentationRedirections }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graphiques -->
                <div class="row">
                    <div class="col-md-8 grid-margin stretch-card">
                        <div class="card-body">
                            <div class="card-title">
                                Redirection Analytics
                            </div>
                            <div class="card-body">
                                <div id="ordersChart" class="chart-container"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 grid-margin stretch-card">
                        <br> <br> <br><br>

                        <div class="card">
 
 

                                <div id="pieChart" style="width: 600px; height: 400px;"></div>

                                <script type="text/javascript">
    // Récupérer les données depuis Laravel (en passant les données PHP dans JavaScript)
    const trafficSources = @json($trafficSources);

    // Préparer les données pour le graphique
    const chartData = trafficSources.map(source => ({
        name: source.source_red,
        value: source.total
    }));

    // Initialiser le graphique ECharts
    var myChart = echarts.init(document.getElementById('pieChart'));

    // Option du graphique
    var option = {
        title: {
            text: 'Sources de Redirection',
            subtext: 'Semaine actuelle et précédente',
            left: 'center'
        },
        tooltip: {
            trigger: 'item',
            formatter: '{a} <br/>{b}: {c} ({d}%)'
        },
        legend: {
            orient: 'vertical',
            left: 'left',
            data: trafficSources.map(source => source.source_red)
        },
        series: [
            {
                name: 'Sources',
                type: 'pie',
                radius: ['40%', '70%'], // Modifier pour un diagramme Doughnut
                avoidLabelOverlap: false,
                itemStyle: {
                    borderRadius: 10, // Coins arrondis
                    borderColor: '#fff',
                    borderWidth: 2
                },
                label: {
                    show: true,
                    position: 'outside', // Position de l'étiquette
                    formatter: '{b}\n{d}%'
                },
                labelLine: {
                    show: true
                },
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                },
                data: chartData
            }
        ]
    };

    // Afficher le graphique
    myChart.setOption(option);
</script>

                            </div>
                     </div>
                </div>

                <div class="row">
                    <!-- Liste des marques -->
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card-body">
                            <div class="card-title">
                                Marques Performance
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-dark">
                                        <thead>
                                            <tr>
                                                <th>Marque</th>
                                                <th>CA (Semaine en cours)</th>
                                                <th>CA (La semaine dernière)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($allMarques as $marque)
                                            <tr>
                                                <td>{{ $marque->marque }}</td>
                                                <td>{{ number_format($marque->chiffre_affaire_current_week, 2) }} TND</td>
                                                <td>{{ number_format($marque->chiffre_affaire_last_week, 2) }} TND</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="row">

                    <!-- Liste des revendeurs -->
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card-body">
                            <div class="card-title">
                                Revendeurs Performance
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-dark">
                                        <thead>
                                            <tr>
                                                <th>Revendeur</th>
                                                <th>CA (Semaine en cours)</th>
                                                <th>CA (La semaine dernière)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($revendeursData as $revendeur)
                                            <tr>
                                                <td>{{ $revendeur->revendeur }}</td>
                                                <td>{{ number_format($revendeur->chiffre_affaire_current_week, 2) }} TND</td>
                                                <td>{{ number_format($revendeur->chiffre_affaire_last_week, 2) }} TND</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                 


                </div>
            </div>
        </div>
    </div>

    <!-- Scripts pour les graphiques -->
    <script>
        // Initialiser le graphique "Redirection Analytics" (ligne)
        var ordersChart = echarts.init(document.getElementById('ordersChart'));

        var ordersChartOptions = {
            title: {
                text: 'Redirection Analytics',
                left: 'center',
                textStyle: {
                    color: '#ffffff'
                }
            },
            tooltip: {
                trigger: 'axis'
            },
            xAxis: {
                type: 'category',
                data: ['Semaine en cours', 'La semaine dernière'],
                axisLine: {
                    lineStyle: { color: '#ffffff' }
                }
            },
            yAxis: {
                type: 'value',
                axisLine: {
                    lineStyle: { color: '#ffffff' }
                }
            },
            series: [
                {
                    name: 'Redirections',
                    type: 'line',
                    data: [{{ $totalRedirectionsCurrentWeek }}, {{ $totalRedirectionsLastWeek }}],
                    color: '#4caf50'
                }
            ]
        };

        ordersChart.setOption(ordersChartOptions);

        // Initialiser le graphique "Traffic Sources" (anneau)
        var donutChart = echarts.init(document.getElementById('donutChart'));

        var donutChartOptions = {
            title: {
                text: 'Traffic Sources',
                left: 'center',
                textStyle: {
                    color: '#ffffff'
                }
            },
            tooltip: {
                trigger: 'item'
            },
            series: [
                {
                    name: 'Traffic Sources',
                    type: 'pie',
                    radius: ['50%', '70%'],
                    data: @json($trafficSources)
                }
            ]
        };

        donutChart.setOption(donutChartOptions);
    </script>
    
    <!-- Inclure le script de Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Inclure Bootstrap JS (Popper.js et Bootstrap.js) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<script>
    // Fonction pour récupérer les notifications
function fetchNotifications() {
    fetch('/notifications')
        .then(response => response.json())
        .then(data => {
            const notificationCount = document.getElementById('notification-count');
            const notificationsList = document.getElementById('notifications-list');
            notificationCount.innerText = data.length; // Affiche le nombre de notifications

            // Affiche les notifications dans une liste
            notificationsList.innerHTML = '';
            data.forEach(notification => {
                const notificationItem = document.createElement('div');
                notificationItem.classList.add('notification');
                notificationItem.innerText = `Redirection ID: ${notification.id} - Non validée depuis plus de 30 minutes`;
                notificationsList.appendChild(notificationItem);
            });
        })
        .catch(error => console.error('Erreur lors de la récupération des notifications:', error));
}

// Interroger les notifications toutes les 30 secondes
setInterval(fetchNotifications, 30000);

// Appeler immédiatement pour initialiser
fetchNotifications();

    </script>
    <style>  #notification-bell {
        position: relative;
    }

    #notification-count {
        position: absolute;
        top: -5px;
        right: -5px;
        font-size: 0.75rem;
    }

    #notifications-list {
        max-height: 300px;
        overflow-y: auto;
    }
        </style>

@include('themes.js')


</body>

</html>
