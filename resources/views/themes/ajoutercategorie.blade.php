<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Categories</title>
  <!-- plugins:css -->
  @include('themes.style')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    @include('themes.header')
    @include('themes.sideleft')

    <!-- partial -->
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
          <div class="col-md-12 grid-margin">
            <div class="row">
              <div class="col-12 col-xl-8 mb-4 mb-xl-0">
               </div>
              <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
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

        <!-- Formulaire d'ajout d'un commercial -->
        <div class="row">
          <div class="col-md-12 stretch-card grid-margin">
            <div class="card">
              <div class="card-body">
                <div class="container mx-auto p-4">
                  <h1 class="text-center mb-4">Ajouter Une  catégorie
</h1>

                  @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                  @endif

                  @if($errors->any())
                    <div class="alert alert-danger">
                      <ul>
                        @foreach($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                      </ul>
                    </div>
                  @endif

                  <form action="{{ route('Categorie.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf <!-- Token CSRF pour la sécurité -->
                    <div class="mb-3">
                      <label for="commercialName" class="form-label">Nom:</label>
                      <input type="text" id="commercialName" class="form-control" name="name" placeholder="Nom de categorie" required />
                    </div>
                    
                   
                    <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                  </form>

               
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Table de liste des commerciaux -->
        <div class="row">
          <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <p class="card-title">Liste de Catégories</p>
                
                <div class="table-responsive">
<!-- Barre de recherche -->
<div class="mb-3">
    <input type="text" id="searchInput" class="form-control" placeholder="Rechercher une catégorie..." oninput="filterTable()">
</div>

<!-- Tableau des catégories -->
<table class="table responsive-table" style="width:100%" id="categoryTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Actions</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($categories) && $categories->count() > 0)
            @foreach ($categories as $categorie)
                <tr>
                    <td>{{ $categorie->id }}</td>
                    <td>{{ $categorie->name }}</td>
                    <td>
                        <!-- Bouton Modifier -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" 
                            data-id="{{ $categorie->id }}" data-name="{{ $categorie->name }}">Modifier</button>
                        
                        <!-- Formulaire de Suppression -->
                       
                    </td>
                    <td>{{ $categorie->created_at->locale('fr_FR')->isoFormat('dddd D MMMM YYYY [à] HH:mm') }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4">Aucune catégorie disponible.</td>
            </tr>
        @endif
    </tbody>
</table>

<!-- Modale pour la modification -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Modifier la catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('categorie.update', 0) }}" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="id" id="editId">
                    <div class="mb-3">
                        <label for="editName" class="form-label">Nom de la catégorie</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
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

<!-- Script -->
<script>
    // Fonction pour filtrer le tableau
    function filterTable() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('#categoryTable tbody tr');

        rows.forEach(row => {
            const name = row.cells[1].innerText.toLowerCase();
            row.style.display = name.includes(searchInput) ? '' : 'none';
        });
    }

    // Remplir le formulaire dans la modale avec les données de la catégorie
    const editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');

        // Mettre à jour l'action du formulaire
        const editForm = document.getElementById('editForm');
        editForm.action = `{{ url('categories') }}/${id}`;

        // Remplir les champs de la modale
        document.getElementById('editId').value = id;
        document.getElementById('editName').value = name;
    });
</script>





                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>

  <!-- plugins:js -->
  @include('themes.js')
</body>

</html>
