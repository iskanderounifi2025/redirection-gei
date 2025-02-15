<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard des Redirections</title>
    @include('themes.style')
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

                                <!-- Formulaire de sélection de la saison et de la marque -->
                                <form action="{{ route('dashboard.redirection') }}" method="GET" class="mb-4">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="saison">Saison</label>
                                            <select name="saison" id="saison" class="form-control">
                                                <option value="">Toutes les saisons</option>
                                                <option value="Hiver" {{ $saison == 'Hiver' ? 'selected' : '' }}>Hiver</option>
                                                <option value="Printemps" {{ $saison == 'Printemps' ? 'selected' : '' }}>Printemps</option>
                                                <option value="Été" {{ $saison == 'Été' ? 'selected' : '' }}>Été</option>
                                                <option value="Automne" {{ $saison == 'Automne' ? 'selected' : '' }}>Automne</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="marque_id">Marque</label>
                                            <select name="marque_id" id="marque_id" class="form-control">
                                                <option value="">Toutes les marques</option>
                                                @foreach ($marques as $marque)
                                                    <option value="{{ $marque->id }}" {{ $marque->id == $marque_id ? 'selected' : '' }}>{{ $marque->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-primary mt-4">Filtrer</button>
                                        </div>
                                    </div>
                                </form>

                                <!-- Tableau des résultats -->
                                <table class="table table-respensive">
                                    <thead>
                                        <tr>
                                            <th>Saison</th>
                                            <th>Produit</th>
                                             <th>Nombre de redirections</th>
                                            <th>Total des ventes</th>
                                            <th>Dernière redirection</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td>{{ $item->saison }}</td>
                                               <td>{{ $item->produit_nom }}</td>
                                                <td>{{ $item->nombre_redirections }}</td>
                                                <td>{{ $item->total_ventes_saison }}</td>
                                                <td>{{ $item->derniere_redirection }}</td>
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

</body>
</html>
