<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard des Redirections</title>
    @include('themes.style')  <!-- Inclure le style CSS -->
</head>
<body>
    <div class="container-scroller">
        @include('themes.header')
        @include('themes.sideleft')

        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                
                                <h3 class="card-title">Rapport de redirections par marque et revendeur</h3>

                               
                                   <!-- 
-->

<div class="row">
    <div class="col-4">
<form method="GET" action="{{ route('report') }}" >
    <label for="annee">Année :</label>
    <select name="annee" id="annee" onchange="this.form.submit()" class="form-control">
        @foreach (range(date('Y'), 2020) as $year)
            <option value="{{ $year }}" {{ request('annee', date('Y')) == $year ? 'selected' : '' }}>
                {{ $year }}
            </option>
        @endforeach
    </select>
</form>
</div>
</div>
<div class="table-responsive"> 
<table class="table table-respensive">
    <thead>
        <tr>
            <th>Revendeur</th>
            <th>CA Total</th>
            @foreach ($marques as $marque)
                <th>CA {{ $marque->name }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($revendeursRanking as $revendeur)
            <tr>
                <td>{{ $revendeur->revendeur }}</td>
                <td>{{ $revendeur->ca_revendeur ? number_format($revendeur->ca_revendeur, 3, ',', ' ') : '0,000' }}</td>
                @foreach ($marques as $marque)
                    @php
                        $caMarque = $reportData->where('revendeur', $revendeur->revendeur)
                                              ->where('marque', $marque->name)
                                              ->first();
                    @endphp
                    <td>{{ $caMarque ? number_format($caMarque->total_ca_marque, 3, ',', ' ') : '0,000' }}</td>
                @endforeach
            </tr>
        @endforeach
        <tr>
            <td><strong>Total</strong></td>
            <td><strong>{{ number_format($totalCaRevendeurs, 3, ',', ' ') }}</strong></td>
            @foreach ($marques as $marque)
                @php
                    $totalCaMarque = $reportData->where('marque', $marque->name)->sum('total_ca_marque');
                @endphp
                <td><strong>{{ number_format($totalCaMarque, 3, ',', ' ') }}</strong></td>
            @endforeach
        </tr>
    </tbody>
</table>
</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('themes.js')  <!-- Inclure les scripts JS -->

    <script>
        function exportTableToExcel(tableId, filename) {
            var table = document.getElementById(tableId);
            var wb = XLSX.utils.table_to_book(table, {sheet: "Rapport"});
            XLSX.writeFile(wb, filename);
        }

        document.getElementById('exportDataTable').addEventListener('click', function () {
            exportTableToExcel('dataTable', 'rapport_redirection_donnees.xlsx');
        });

        document.getElementById('exportRevendeursTable').addEventListener('click', function () {
            exportTableToExcel('revendeursTable', 'rapport_top_revendeurs.xlsx');
        });

        document.getElementById('exportSkuTable').addEventListener('click', function () {
            exportTableToExcel('skuTable', 'rapport_top_sku.xlsx');
        });
    </script>
</body>
</html>
