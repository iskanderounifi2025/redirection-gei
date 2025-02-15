<!DOCTYPE html>
<html lang="fr">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Liste des Produits</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Include styles -->
  @include('themes.style')
</head>
<body>
  <div class="container-scroller">
    @include('themes.header')
    @include('themes.sideleft')

    <!-- Main Content -->
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
          <div class="col-md-12 grid-margin">
            <div class="row">
              <div class="col-12 col-md-8 mb-4 mb-md-0">
               <!-- You can add additional content here if needed -->
              </div>
              <div class="col-12 col-md-4">
                <div class="d-flex justify-content-md-end">
                  <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button">
                    <i class="ti-calendar"></i> Aujourd'hui ({{ now()->format('d M Y') }} - {{ now()->format('H:i') }})
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <p class="card-title">Liste des Produits</p>
                <!-- Notification de succès -->
              @if(session('success'))
                <div class="alert alert-success">
                  {{ session('success') }}
                </div>
              @endif

              <!-- Notification d'erreur -->
              @if(session('error'))
                <div class="alert alert-danger">
                  {{ session('error') }}
                </div>
              @endif

                 <!-- Barre de recherche -->
                 <div class="row mb-3">

                 <input type="text" id="searchInput" class="form-control mb-3" placeholder="Rechercher un revendeur..." onkeyup="searchTable()">
              
                  </div>
                  <!-- Tableau des produits -->
                  <div class="table-responsive">
                    <table id="revendeursTable" class="table table-respensive">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>SKU</th>
                          <th>Produit</th>
                          <th>Marque</th>
                          <th>Prix</th>
                          <th>Statut</th>
                          <th>Date d'ajout</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <!-- Données insérées dynamiquement -->
                        @foreach ($produits as $produit)
                          <tr>
                            <td>{{ $produit->id }}</td>
                            <td>{{ $produit->sku }}</td>
                            <td>{{ $produit->name }}</td>
                            <td>{{ $produit->brand ? $produit->brand->name : 'Non renseignée' }}</td>
                            <td>{{ number_format($produit->price, 2) }} DT</td>
                            <td>
  <form action="{{ route('produit.updateEtat', $produit->id) }}" method="POST" style="display:inline;">
    @csrf
    @method('PATCH') <!-- Utilisez @method('PATCH') pour indiquer qu'il s'agit d'une requête PATCH -->
    
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" name="etat" role="switch" onchange="this.form.submit()" {{ $produit->etat == 1 ? 'checked' : '' }}>
      <label class="form-check-label">
        {{ $produit->etat == 1 ? 'Activer' : 'Désactiver' }}
      </label>
    </div>
  </form>
</td>
                            <td>{{ $produit->created_at->format('d/m/Y') }}</td>
                            <td>
                              <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editProductModal-{{ $produit->id }}"><i class="ti-slice
"></i></button>
                              
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  <!-- Pagination -->
                  <div class="d-flex justify-content-center mt-4">
                    {{ $produits->links('pagination::bootstrap-5') }}
                  </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Modifier un Produit -->
        @foreach ($produits as $produit)
    <!-- Modal pour chaque produit -->
    <div class="modal fade" id="editProductModal-{{ $produit->id }}" tabindex="-1" aria-labelledby="editProductModalLabel-{{ $produit->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('produits.update', $produit->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProductModalLabel-{{ $produit->id }}">Modifier le Produit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Champ SKU -->
                        <div class="mb-3">
                            <label for="sku" class="form-label">SKU</label>
                            <input type="text" class="form-control" name="sku" value="{{ $produit->sku }}" required>
                        </div>

                        <!-- Champ Nom -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom</label>
                            <input type="text" class="form-control" name="name" value="{{ $produit->name }}" required>
                        </div>

                        <!-- Champ Prix -->
                        <div class="mb-3">
                            <label for="price" class="form-label">Prix</label>
                            <input type="number" class="form-control" name="price" value="{{ $produit->price }}" step="0.01" required>
                        </div>

                        <!-- Champ Image (optionnel) -->
                        <div class="mb-3">
                            <label for="image_path" class="form-label">Image</label>
                            <input type="file" class="form-control" name="image_path">
                            @if ($produit->image_path)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $produit->image_path) }}" alt="Image du produit" width="100">
                                </div>
                            @endif
                        </div>

                        <!-- Case à cocher pour l'état -->
                       
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

      </div>
    </div>
  </div>

  <!-- Include Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Script pour la recherche -->
  <script>
  let debounceTimeout;

  function searchTable() {
    clearTimeout(debounceTimeout);

    debounceTimeout = setTimeout(function() {
      const input = document.getElementById('searchInput');
      const filter = input.value.toLowerCase();
      const rows = document.querySelectorAll('#revendeursTable tbody tr');

      rows.forEach((row) => {
        const cells = row.getElementsByTagName('td');
        let match = false;

        for (let i = 0; i < cells.length; i++) {
          if (cells[i] && cells[i].textContent.toLowerCase().includes(filter)) {
            match = true;
            break;
          }
        }

        row.style.display = match ? '' : 'none';
      });
    }, 300); // 300ms de délai avant d'effectuer la recherche
  }
</script>



  <!-- Include JS files -->
  @include('themes.js')
</body>
</html>
