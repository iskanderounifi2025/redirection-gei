<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Dashboard Pour Marque</title>
  @include('themes.style') <!-- Include your styles -->
<!-- Ajouter ECharts via CDN -->
<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.2/dist/echarts.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
      /* Custom canvas styles for chart */
      #brandRevenueChart {
          width: 100%;
          max-width: 800px;
          height: 400px;
      }
        /* Custom canvas styles for chart */
    #brandRevenueChart {
        width: 100% !important;   /* Ensures it takes full width of its container */
        height: 400px;            /* Default height, can be adjusted */
        max-width: 800px;         /* Max width to avoid stretching on large screens */
    }

    /* Responsive chart on mobile */
    @media (max-width: 768px) {
        #brandRevenueChart {
            height: 300px; /* Adjust height on smaller screens */
        }
    }
  </style>
</head>

<body>
  <div class="container-scroller">
    <!-- Include Navbar and Sidebar -->
    @include('themes.header') <!-- Header -->
    @include('themes.sideleft') <!-- Sidebar -->

    <!-- Main Panel -->
    <div class="main-panel">
      <div class="content-wrapper">
<div class=row">
<div class="row">
    <!-- Carte Revenus Année (YTD) -->
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex flex-row flex-wrap text-center text-sm-left align-items-center">
                    <div class="ms-sm-3 ms-md-0 ms-xl-3 mt-2 mt-sm-0 mt-md-2 mt-xl-0">
                        <h6 class="mb-0">Revenus Année (YTD) :</h6>
                        <p class="text-muted mb-1">
                            <i class="fas fa-calendar-day"></i> {{ number_format($totals['ytd'], 3) }} TND
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Carte Revenus Mois (MTD) -->
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex flex-row flex-wrap text-center text-sm-left align-items-center">
                    <div class="ms-sm-3 ms-md-0 ms-xl-3 mt-2 mt-sm-0 mt-md-2 mt-xl-0">
                        <h6 class="mb-0">Revenus Mois (MTD) :</h6>
                        <p class="text-muted mb-1">
                            <i class="fas fa-calendar-month"></i> {{ number_format($totals['mtd'], 3) }} TND
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Carte Revenus Derniers 7 Jours -->
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex flex-row flex-wrap text-center text-sm-left align-items-center">
                    <div class="ms-sm-3 ms-md-0 ms-xl-3 mt-2 mt-sm-0 mt-md-2 mt-xl-0">
                        <h6 class="mb-0">Revenus Derniers 7 Jours :</h6>
                        <p class="text-muted mb-1">
                            <i class="fas fa-calendar-week"></i> {{ number_format($totals['l7d'], 3) }} TND
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Carte Revenus Aujourd'hui -->
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex flex-row flex-wrap text-center text-sm-left align-items-center">
                    <div class="ms-sm-3 ms-md-0 ms-xl-3 mt-2 mt-sm-0 mt-md-2 mt-xl-0">
                        <h6 class="mb-0">Revenus Aujourd'hui :</h6>
                        <p class="text-muted mb-1">
                            <i class="fas fa-calendar-day"></i> {{ number_format($totals['today'], 3) }} TND
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Carte Revenus Hier -->
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex flex-row flex-wrap text-center text-sm-left align-items-center">
                    <div class="ms-sm-3 ms-md-0 ms-xl-3 mt-2 mt-sm-0 mt-md-2 mt-xl-0">
                        <h6 class="mb-0">Revenus Hier :</h6>
                        <p class="text-muted mb-1">
                            <i class="fas fa-calendar-minus"></i> {{ number_format($totals['yesterday'], 3) }} TND
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>


        <div class="row">
          <!-- Tableau des données par marque -->
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <h2 class="card-title">Statistiques Détaillées</h2>
<div class="table-respensive>
                <!-- Table of Redirection Statistics -->
                <script>
    $(document).ready(function() {
        $('#brandsTable').DataTable({
            "responsive": true
        });
    });
</script>
<div class="table-respensive">
<table class="table table-reseponsive" style="width:100%">
        <thead>
            <tr>
                <th>Marque</th>
              <!--  <th>Total Redirections</th>-->
                <th>Revenus  (YTD)</th>
                <th>% YTD</th>
                <th>Revenus  (MTD)</th>
                <th>% MTD</th>
                <th>Revenus 7 Derniers Jours</th>
                <th>% Derniers 7 Jours</th>
                <th>Revenus Aujourd'hui</th>
                <th>% Aujourd'hui</th>
            </tr>
        </thead>
        <tbody>
            @foreach($RedirectionStatisticsBrand as $stat)
            <tr>
                <td>{{ $stat->brand_name }}</td>
                 <!--   <td>{{ $stat->total_redirections }}</td>-->
                <td>{{ number_format($stat->ytd_revenue, 3) }} TND</td>
                <td>{{ number_format($stat->ytd_percentage, 3) }}%</td>
                <td>{{ number_format($stat->mtd_revenue, 3) }} TND</td>
                <td>{{ number_format($stat->mtd_percentage, 3) }}%</td>
                <td>{{ number_format($stat->l7d_revenue, 3) }} TND</td>
                <td>{{ number_format($stat->l7d_percentage, 3) }}%</td>
                <td>{{ number_format($stat->today_revenue, 3) }} TND</td>
                <td>{{ number_format($stat->today_percentage,3) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>

    <!-- Totaux Globaux -->
  
 
            
    <!-- Graphique des Revenus -->
    <div class="mt-4">
    <div id="brandRevenueChart" style="width: 100%; height: 400px;"></div>
    </div>
</div>
 
                </div>
                 
                </div>
            </div>
          </div>
        </div>
      </div>
     </div>
   </div>

   @include('themes.js') <!-- Scripts -->
   <script>
    const chartData = @json($chartData);

    // Initialiser le graphique ECharts
    const chart = echarts.init(document.getElementById('brandRevenueChart'));

    // Configuration et options du graphique
    const options = {
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'cross',
                crossStyle: {
                    color: '#999'
                }
            },
            formatter: function (params) {
                let tooltipContent = '';
                params.forEach(function (param) {
                    const value = param.value.toLocaleString('fr-FR', { style: 'currency', currency: 'TND' });
                    tooltipContent += `${param.seriesName}: ${value}<br/>`;
                });
                return tooltipContent;
            }
        },
        legend: {
            data: chartData.datasets.map(dataset => dataset.label),
            top: 'top'
        },
        xAxis: {
            type: 'category',
            data: chartData.labels,
            axisLabel: {
                rotate: 45,
                interval: 0
            },
            axisPointer: {
                type: 'shadow'
            },
            title: {
                text: 'Marques',
                left: 'center',
                top: 'bottom'
            }
        },
        yAxis: {
            type: 'value',
            title: {
                text: 'Revenue (TND)',
                left: 'center',
                top: 'middle'
            },
            axisLabel: {
                formatter: '{value} TND'
            }
        },
        series: chartData.datasets.map(dataset => ({
            name: dataset.label,
            type: 'line',
            data: dataset.data,
            smooth: true,
            itemStyle: {
                color: '#4CAF50', // Vous pouvez personnaliser la couleur
            }
        }))
    };

    // Utiliser les options et afficher le graphique
    chart.setOption(options);
</script>

</body>

</html>
