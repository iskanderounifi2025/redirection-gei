<!DOCTYPE html>
<html lang="fr-tn">

<head>
  <!-- Meta Tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Liste des Marques</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Optionally include any additional styles -->
  @include('themes.style')

</head>

<body>
  <div class="container-fluid">
    @include('themes.header')
    @include('themes.sideleft')

    <div class="main-panel">
      <div class="content-wrapper">
        <main class="container mt-4">
          <!-- Page Header -->
          <div class="row mb-4">
            <div class="col-6">
              <h4>Liste des Marques</h4>
            </div>
            <div class="col-6 text-end">
              <button class="btn btn-light">
                <i class="mdi mdi-calendar"></i> Aujourd'hui ({{ now()->format('d M Y') }} - {{ now()->format('H:i') }})
              </button>
            </div>
          </div>

          <!-- Table Section -->
          <div class="card">
            <div class="card-body">
              <p class="card-title">Liste des Marques</p>
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

              <div class="table-responsive">
                <table id="brandTable" class="table">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Marque</th>
                      <th>Action</th>
                      <th>Status</th>
                      <th>Date Ajoutée</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($brands as $brand)
                    <tr>
                      <td>
                        <img src="{{ asset('logos/'. $brand->logo) }}" alt="Image de {{ $brand->name }}" width="50">
                      </td>
                      <td>{{ $brand->name }}</td>
                      <td>
                        <!-- Bouton Modifier -->
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBrandModal{{ $brand->id }}">
                          <i class="ti-pencil-alt"></i>
                        </button>
                        <!-- Bouton Supprimer -->
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteBrandModal{{ $brand->id }}">
                          <i class="ti-trash"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="">
                          <i class="ti-trash"></i>
                        </button>
                      </td>
                      <td>
                        <i class="ti-check-box"></i> <!-- Status Actif -->
                      </td>
                      <td>{{ $brand->created_at->format('d M Y') }}</td>
                    </tr>

                    <!-- Modal Modifier -->
                    <div class="modal fade" id="editBrandModal{{ $brand->id }}" tabindex="-1" aria-labelledby="editBrandLabel{{ $brand->id }}" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="editBrandLabel{{ $brand->id }}">Modifier la Marque</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <form action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="mb-3">
            <label for="name" class="form-label">Nom de la Marque</label>
            <input type="text" name="name" class="form-control" value="{{ $brand->name }}" required>
        </div>
        <div class="mb-3">
            <label for="logo" class="form-label">Logo</label>
            <input type="file" name="logo" class="form-control">
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </div>
</form>

                        </div>
                      </div>
                    </div>

                    <!-- Modal Supprimer -->
                    <div class="modal fade" id="deleteBrandModal{{ $brand->id }}" tabindex="-1" aria-labelledby="deleteBrandLabel{{ $brand->id }}" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="deleteBrandLabel{{ $brand->id }}">Supprimer la Marque</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <form action="{{ route('brands.destroy', $brand->id) }}" method="POST">
    @csrf
    @method('DELETE')
    <div class="modal-body">
        <p>Êtes-vous sûr de vouloir supprimer la marque <strong>{{ $brand->name }}</strong> ?</p>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-danger">Supprimer</button>
    </div>
</form>

                        </div>
                      </div>
                    </div>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
  </div>
  @include('themes.js')

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
