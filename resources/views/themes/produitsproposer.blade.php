<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tableau de Bord - Produit</title>

  <!-- Inclure les styles -->
  @include('themes.style')

  <style>
    /* Assurez-vous que la table soit responsive */
    .table-responsive {
      overflow-x: auto;
    }
  </style>
</head>

<body>
  <div class="container-scroller">
    @include('themes.header')
    @include('themes.sideleft')

    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
          <div class="col-md-12 grid-margin">
            <div class="row">
              <div class="col-12 col-md-8 mb-4 mb-md-0"></div>
              <div class="col-12 col-md-4">
                <div class="d-flex justify-content-md-end">
                  <div>
                    <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" id="dropdownMenuDate2" aria-haspopup="true" aria-expanded="true">
                      <i class="mdi mdi-calendar"></i> Aujourd'hui ({{ now()->format('d M Y') }} - {{ now()->format('H:i') }})
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Formulaire pour la sélection de saison -->
        <div class="row">
          <div class="col-md-12 grid-margin">
            <div class="card">
              <div class="card-body">
        <form method="GET" action="{{ route('proposeProduitsParSaison') }}">
          <div class="form-group">
            <label for="saison">Choisir une saison :</label>
            <select name="saison" id="saison" class="form-control">
              <option value="">-- Sélectionnez --</option>
              <option value="Hiver" {{ $saison === 'Hiver' ? 'selected' : '' }}>Hiver</option>
              <option value="Printemps" {{ $saison === 'Printemps' ? 'selected' : '' }}>Printemps</option>
              <option value="Été" {{ $saison === 'Été' ? 'selected' : '' }}>Été</option>
              <option value="Automne" {{ $saison === 'Automne' ? 'selected' : '' }}>Automne</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Rechercher</button>
        </form>

        <!-- Message de succès -->
        @if (session('success'))
        <div class="alert alert-success mt-4">
          {{ session('success') }}
        </div>
        @endif

        <!-- Affichage des suggestions -->
        @if (isset($suggestions) && count($suggestions) > 0)
        <div class="table-respensive">
          <table class="table table-respensive">
            <thead>
              <tr>
                <th>Nom du produit</th>
                <th>Marque</th>
                <th>Nombre de redirections</th>
                <th>Total des ventes</th>
                <th>Dernière redirection</th>
                <th>Suggestion</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($suggestions as $suggestion)
              <tr>
                <td>{{ $suggestion->produit_nom }}</td>
                <td>{{ $suggestion->marque_nom }}</td>
                <td>{{ $suggestion->nombre_redirections }}</td>
                <td>{{ $suggestion->total_ventes }}</td>
                <td>{{ \Carbon\Carbon::parse($suggestion->derniere_redirection)->locale('fr')->format('d F Y') }}</td>
                <td>{{ $suggestion->suggestion }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @elseif (isset($saison))
        <div class="alert alert-info mt-4">Aucun produit trouvé pour la saison sélectionnée.</div>
        @endif
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
