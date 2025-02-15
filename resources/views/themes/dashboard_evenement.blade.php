<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chiffre d'Affaire par Événement</title>
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
                            <p class="card-title">Chiffre d'Affaire par Événement</p>                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Nom de l'Événement</th>
                                                <th>Chiffre d'Affaire</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($chiffreAffaireParEvenement as $evenement)
                                                <tr>
                                                    <td>{{ $evenement['evenement_nom'] }}</td>
                                                    <td>{{ number_format($evenement['chiffre_affaire'], 2) }} TND</td>
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
