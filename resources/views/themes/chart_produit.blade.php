<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques de Chiffre d'Affaires</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Statistiques de Chiffre d'Affaires par Marque</h1>
    
    @if(isset($chiffreAffaires) && count($chiffreAffaires) > 0)
        <table>
            <thead>
                <tr>
                    <th>Marque</th>
                    <th>Chiffre d'Affaires (TND)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($chiffreAffaires as $item)
                    <tr>
                        <td>{{ $item->marques }}</td>
                        <td>{{ number_format($item->total_chiffre_affaires, 3) }} TND</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Aucune donnée de chiffre d'affaires disponible.</p>
    @endif

    <canvas id="chiffreAffairesChart" width="400" height="200"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        @if(isset($chiffreAffaires) && count($chiffreAffaires) > 0)
            var ctx = document.getElementById('chiffreAffairesChart').getContext('2d');
            var chiffreAffairesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chiffreAffaires->pluck('marques')->all()) !!}, // Liste des marques
                    datasets: [{
                        label: 'Chiffre d\'Affaires (TND)',
                        data: {!! json_encode($chiffreAffaires->pluck('total_chiffre_affaires')->all()) !!}, // Totaux du chiffre d'affaires
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        @else
            console.log("Aucune donnée de chiffre d'affaires disponible.");
        @endif
    </script>
</body>
</html>
