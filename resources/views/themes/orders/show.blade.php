<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Détails de la Commande - {{ $order->id }}</title>
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
            <h1 class="text-center mb-4 display-4">Détails de la Commande #{{ $order->id }}</h1>
          </div>
        </div>

        <!-- Section Détails de la commande -->
        <div class="row">
          <div class="col-md-12 grid-margin">
            <div class="card">
              <div class="card-body">
                <h2 class="card-title">Informations sur la Commande</h2>
                <p><strong>Date :</strong> {{ $order->created_at }}</p>
                <p><strong>Statut :</strong> {{ $order->status }}</p>
                <p><strong>Montant Total :</strong> {{ $order->total_amount }}€</p>
                <p><strong>Produits :</strong></p>
                <ul>
                  @foreach ($order->products as $product)
                    <li>{{ $product->name }} - {{ $product->quantity }} x {{ $product->price }}€</li>
                  @endforeach
                </ul>
                <a href="{{ route('order.index', ['siteId' => $site->id]) }}" class="btn btn-secondary btn-sm">Retour à la liste des commandes</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      @include('themes.js')
    </div>
  </div>
</body>

</html>
