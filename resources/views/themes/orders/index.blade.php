<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Liste des Commandes</title>
  @include('themes.style')
</head>

<body>
  <div class="container-scroller">
    @include('themes.header')
    @include('themes.sideleft')

    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
          <div class="col-md-12 grid-margin">
            <h1 class="text-center mb-4 display-4">Liste des Sites</h1>
          </div>
        </div>

        <!-- Liste des sites -->
        <div class="row">
          <div class="col-md-12 grid-margin">
            <div class="card">
              <div class="card-body">
                <h2 class="card-title">Sites Disponibles</h2>
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Nom du Site</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($sites as $site)
                      <tr>
                        <td>{{ $site->id }}</td>
                        <td>{{ $site->name }}</td>
                        <td>
                          <!-- Lien vers la page des commandes du site -->
                          <a href="{{ route('site.orders', ['siteId' => $site->id]) }}" class="btn btn-primary btn-sm">Voir les Commandes</a>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="3" class="text-center">Aucun site disponible.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Si un site est sélectionné, afficher ses commandes -->
        @isset($site)
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h2 class="card-title">Liste des Commandes pour le site: {{ $site->name }}</h2>
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Date de Commande</th>
                        <th>Statut</th>
                        <th>Montant Total</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($orders as $order)
                        <tr>
                          <td>{{ $order->id }}</td>
                          <td>{{ $order->created_at }}</td>
                          <td>{{ $order->status }}</td>
                          <td>{{ $order->total_amount }}€</td>
                          <td>
                            <!-- Voir la commande -->
                            <a href="{{ route('order.show', ['siteId' => $site->id, 'orderId' => $order->id]) }}" class="btn btn-info btn-sm">Voir</a>
                          </td>
                        </tr>
                      @empty
                        <tr>
                          <td colspan="5" class="text-center">Aucune commande disponible pour ce site.</td>
                        </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        @endisset

      </div>
      @include('themes.js')
    </div>
  </div>
</body>

</html>
