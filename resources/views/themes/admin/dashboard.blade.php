<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Dashboard</title>

    <!-- Include styles -->
    @include('themes.style')
    
    <!-- Charger Google Charts -->
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.0/dist/echarts.min.js"></script>
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
                <h1 class="mb-4">Dashboard</h1>

                <!-- Statistiques sous forme de cartes -->
                <div class="row">
    <!-- Total Redirections -->
    <div class="col-md-4 mb-4">
        <div class="card p-4 shadow-sm">
        <button type="button" class="btn btn-inverse-primary btn-rounded btn-icon"><i class="ti-import
"></i></button>
<h5 class="mb-4">Total des Redirections en année encours:</h5>
<p class="fs-30 mb-2">{{ $data['totalRedirectionsCurrentYear'] }}</p>
        </div>
    </div>
    <!-- Total Redirections 2024 -->
    <div class="col-md-4 mb-4">
        <div class="card p-4 shadow-sm">
        <button type="button" class="btn btn-inverse-primary btn-rounded btn-icon"><i class="ti-import
"></i></button>

            <h5 class="mb-4">Total des Redirections en année dernier:</h5>
            <p class="fs-30 mb-2">{{ $data['totalRedirectionsLastYear'] }}</p>
        </div>
    </div> 
    <!-- Pourcentage d'augmentation des Redirections -->
    
    <!-- Chiffre d'affaire en 2023 -->
    <div class="col-md-4 mb-4">
        <div class="card p-4 shadow-sm">
        <button type="button" class="btn btn-inverse-primary btn-rounded btn-icon"><i class="ti-money
"></i></button>
<h5 class="mb-4">CA en année dernier::</h5>
<h6 class="fs-30 mb-2">{{ number_format($data['chiffreAffaireLastYear'] ?? 0, 3) }} TND</h6>
        </div>
    </div>
    <!-- Chiffre d'affaire en 2024 -->
    <div class="col-md-4 mb-4">
        <div class="card p-4 shadow-sm">
        <button type="button" class="btn btn-inverse-primary btn-rounded btn-icon"><i class="ti-money
        "></i></button>
        <h5 class="mb-4">CA  en année encours:</h5>
            <h2 class="fs-30 mb-2">{{ number_format($data['chiffreAffaireCurrentYear'] ?? 0, 3) }} TND</h2>
        </div>
    </div>
    <!-- Pourcentage d'augmentation du Chiffre d'affaire -->
    <div class="col-md-4 mb-4">
        <div class="card p-4 shadow-sm">
        <button type="button" class="btn btn-inverse-primary btn-rounded btn-icon"><i class="ti-arrow-up
"></i></button>
<h5 class="mb-4">Augmentation du Chiffre d'Affaire:</h5>
            <h2>
           {{ number_format($data['augmentationChiffreAffaire'] ?? 0, 3) }} TND
            
            </h2>
            <small class="text-muted"> @php
                    $augmentationChiffreAffairePercentage = ($data['chiffreAffaireCurrentYear'] && $data['chiffreAffaireLastYear']) 
                        ? (($data['chiffreAffaireCurrentYear'] - $data['chiffreAffaireLastYear']) / $data['chiffreAffaireLastYear']) * 100
                        : 0;
                @endphp
                ({{ number_format($augmentationChiffreAffairePercentage, 2) }}%)   <i class="ti-arrow-top-right
"></i> </small>

        </div>

        
    </div>
    <div class="col-md-4 mb-4">
        <div class="card p-4 shadow-sm">
        <button type="button" class="btn btn-inverse-primary btn-rounded btn-icon"><i class="ti-arrow-up
"></i></button>
<h5 class="mb-4">Augmentation des Redirections:</h5>
            <h2 class="fs-30 mb-2">
                {{ $data['augmentationRedirections'] }}</h2> <small class="text-muted">
                @php
                    $augmentationRedirectionsPercentage = ($data['totalRedirectionsCurrentYear'] && $data['totalRedirectionsLastYear']) 
                        ? (($data['totalRedirectionsCurrentYear'] - $data['totalRedirectionsLastYear']) / $data['totalRedirectionsLastYear']) * 100
                        : 0;
                @endphp
                ({{ number_format($augmentationRedirectionsPercentage, 2) }}%)
                <i class="ti-arrow-top-right
                "></i> </small>
        </div>
    </div>


                    <!-- Commandes annulées -->
                  

                    <!-- Bénéfices Revenus -->
                

                    <!-- Répartition des genres des clients -->
                  
                </div>
      
                <!-- Graphique des revenus -->
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="card p-4 shadow-sm">
                            <h3>CA par Marque</h3>
    <div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Marque</th>
                <th>CA année dernier</th>
                <th>CA année encoure</th>
                <th>Augmentation (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['allMarques'] as $row)
                @php
                    $chiffreCurrentYear = $row->chiffre_affaire_current_year ?? 0;
                    $chiffreLastYear = $row->chiffre_affaire_last_year ?? 0;
                    $augmentation = 0;
                    if ($chiffreLastYear > 0) {
                        $augmentation = (($chiffreCurrentYear - $chiffreLastYear) / $chiffreLastYear) * 100;
                    }
                @endphp
                <tr>
                    <td>{{ $row->marque }}</td>
                    <td>{{ number_format($chiffreLastYear, 3) }} TND</td>
                    <td>{{ number_format($chiffreCurrentYear, 3) }} TND</td>

                    <td>
                        @if ($augmentation > 0)
                            <span class="text-success">{{ number_format($augmentation, 2) }}% <i class="ti-arrow-up"></i></span>
                        @elseif ($augmentation < 0)
                            <span class="text-danger">{{ number_format($augmentation, 2) }}%<i class="ti-arrow-down"></i></span>
                        @else
                            <span>0%</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
                    </div>
                </div>
                </div>
                </div>
                <!-- Sources de Trafic -->
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="card p-4 shadow-sm">
                            <h5>Sources de Trafic</h5>
                            <div  style="position: relative; width: 879px; height: 400px; padding: 0px; margin: 0px; border-width: 0px; cursor: default;">
    
                            <canvas id="trafficSourcesChart" data-zr-dom-id="zr_0" width="879" height="400" style="position: absolute; left: 0px; top: 0px; width: 879px; height: 400px; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); padding: 0px; margin: 0px; border-width: 0px;"></canvas>
                            </div>

                        </div>
                    </div>
                      <div class="col-md-12 mb-4">
                        <div class="card p-4 shadow-sm">
                            <h5>Revendeurs année dernier VS année encours</h5>
                            <div  style="position: relative; width: 879px; height: 400px; padding: 0px; margin: 0px; border-width: 0px; cursor: default;">
 
    <canvas id="trafficRevendeursData" data-zr-dom-id="zr_0" width="600" height="400" style="position: absolute; left: 0px; top: 0px; width: 879px; height: 400px; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); padding: 0px; margin: 0px; border-width: 0px;"></canvas>
    </div>

                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="card p-4 shadow-sm">
                    
        <h5 class="mb-0">Statistiques par Sexe</h5>


        <div id="sexChart" style="width: 100%; height: 400px;"></div>
        <div class="d-flex justify-content-center mt-3">
            <div class="text-center mx-3">
                <i class="fas fa-male fa-2x text-primary"></i>
                <p>Homme</p>
            </div>
            <div class="text-center mx-3">
                <i class="fas fa-female fa-2x text-pink"></i>
                <p>Femme</p>
            </div>
        </div>
  

                </div>
                </div>
                </div>
            </div>

        </div>

        <!-- Footer -->
        @include('themes.js')
        <script>
        // ECharts for traffic sources chart
        var trafficSourcesChart = echarts.init(document.getElementById('trafficSourcesChart'));
        var trafficSourcesData = @json($data['trafficSources']); // Pass the trafficSources data to JavaScript

        // Prepare the chart data
        var trafficLabels = trafficSourcesData.map(function(item) {
            return item.source_red;
        });

        var trafficValues = trafficSourcesData.map(function(item) {
            return item.total;
        });
        var trafficSourcesOption = {
    tooltip: {
        trigger: 'item',
        formatter: '{a} <br/>{b}: {c} ({d}%)'
    },
    legend: {
        orient: 'vertical',
        left: 'left'
    },
    series: [{
        name: 'Traffic Sources',
        type: 'pie',
        radius: '50%',
        data: trafficSourcesData.map(function(item, index) {
            return { value: item.total, name: item.source_red };
        }),
        label: {
            show: true, // Show labels
            formatter: '{b}: {c}' // Display the name and value
        },
        emphasis: {
            itemStyle: {
                shadowBlur: 10,
                shadowOffsetX: 0,
                shadowColor: 'rgba(0, 0, 0, 0.5)'
            }
        }
    }]
};
trafficSourcesChart.setOption(trafficSourcesOption);


        // ECharts for Revendeurs chart
        var trafficRevendeursData = @json($data['revendeursData']); // Pass the revendeurs data to JavaScript
        var revendeursLabels = trafficRevendeursData.map(function(item) {
            return item.revendeur;
        });

        var chiffreAffaireCurrentYear = trafficRevendeursData.map(function(item) {
            return item.chiffre_affaire_current_year;
        });

        var chiffreAffaireLastYear = trafficRevendeursData.map(function(item) {
            return item.chiffre_affaire_last_year;
        });

        var trafficRevendeursOption = {
            
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                }
            },
            legend: {
                data: ['Chiffre d\'Affaire 2023', 'Chiffre d\'Affaire 2024']
            },
            xAxis: {
                type: 'category',
                data: revendeursLabels
            },
            yAxis: {
                type: 'value'
            },
            series: [
                {
                    name: 'Chiffre d\'Affaire 2023',
                    type: 'bar',
                    data: chiffreAffaireLastYear
                },
                {
                    name: 'Chiffre d\'Affaire 2024',
                    type: 'bar',
                    data: chiffreAffaireCurrentYear
                }
            ]
        };
        var trafficRevendeursChart = echarts.init(document.getElementById('trafficRevendeursData'));
        trafficRevendeursChart.setOption(trafficRevendeursOption);
    </script>
    </div>

    <script>
    // Extraire les données depuis le backend
    const sexStatistics = @json($data['sexStatistics']);
    const currentYear = @json($data['currentYear']);
    const lastYear = @json($data['lastYear']);

    // Préparer les données pour ECharts
    const labels = sexStatistics.map(stat => stat.sexe); // Labels: 'Homme', 'Femme'
    const dataCurrentYear = sexStatistics.map(stat => stat.total_current_year); // Data for current year
    const dataLastYear = sexStatistics.map(stat => stat.total_last_year); // Data for last year

    // Initialiser le graphique
    const chartDom = document.getElementById('sexChart');
    const myChart = echarts.init(chartDom);

    // Configurer les options du graphique
    const option = {
        tooltip: {
            trigger: 'axis',
        },
        legend: {
            data: [`Année ${currentYear}`, `Année ${lastYear}`],
        },
        xAxis: {
            type: 'category',
            data: labels,
        },
        yAxis: {
            type: 'value',
        },
        series: [
            {
                name: `Année ${currentYear}`,
                type: 'bar',
                data: dataCurrentYear,
                itemStyle: {
                    color: '#4A90E2', // Couleur pour les hommes
                },
            },
            {
                name: `Année ${lastYear}`,
                type: 'bar',
                data: dataLastYear,
                itemStyle: {
                    color: '#FF647C', // Couleur pour les femmes
                },
            },
        ],
    };

    // Appliquer les options
    myChart.setOption(option);

    // Rendre le graphique responsive
    window.addEventListener('resize', () => {
        myChart.resize();
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>

    </body>
    </html>
