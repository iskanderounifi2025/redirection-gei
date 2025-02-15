 
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chiffre d'Affaire par Marque et Événement</title>
    @include('themes.style')
</head>
<body>
    <div class="container-scroller">
        @include('themes.header')
        @include('themes.sideleft')

        <!-- Affichage du chiffre d'affaire par événement -->
        <div class="main-panel">
            <div class="content-wrapper">
                <h1 class="text-center mb-4"></h1>

                <div class="row">
                    <div class="col-md-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                            <p class="card-title">Chiffre d'Affaire par Marque et Événement</p>                                <div class="table-responsive">
                           <div class="table table-respensive">
                            <table class="table table-responsive">
            <thead>
                <tr>
                    <th>Nom de la Marque</th>
                    <th>Budget Total</th>
                    <th>Revenus Totaux</th>
                    <th>ROI (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                <tr>
                    <td>{{ $item->marque_name ?? 'Non Défini' }}</td>
                    <td>{{ number_format($item->total_budget, 3, ',', ' ') }} TND</td>
                    <td>{{ number_format($item->total_revenue, 3, ',', ' ') }} TND</td>
                    <td>{{ number_format($item->roi_percentage, 3, ',', ' ') }}%</td>
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

        @include('themes.js')
    </div>
</body>
</html>
