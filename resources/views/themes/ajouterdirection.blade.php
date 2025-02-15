<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Directions</title>
  <!-- plugins:css -->
  @include('themes.style')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
  <div class="container-scroller">
    <!-- Navbar et Sidebar -->
    @include('themes.header')
    @include('themes.sideleft')

    <!-- Contenu principal -->
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
          <div class="col-md-12 grid-margin">
            <div class="row">
              <div class="col-12 col-md-8 mb-4 mb-md-0"></div>
              <div class="col-12 col-md-4">
                <div class="d-flex justify-content-md-end">
                  <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" id="dropdownMenuDate2">
                    <i class="mdi mdi-calendar"></i> Aujourd'hui ({{ now()->format('d M Y') }} - {{ now()->format('H:i') }})
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Formulaire d'ajout d'une direction -->
        <div class="row">
          <div class="col-md-12 stretch-card grid-margin">
            <div class="card">
              <div class="card-body">
                <div class="container mx-auto p-4">
                  <h1 class="text-center mb-4">Ajouter Direction</h1>

                  <!-- Alertes de validation -->
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

                  <form action="{{ route('directions.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                      <label for="nom" class="">Nom Prénom :</label>
                      <input type="text" id="nom" class="form-control" name="nom" placeholder="Entrez le nom" required />
                    </div>
                    <div class="mb-3">
                      <label for="prenom" class="">Poste:</label>
                      <input type="text" id="prenom" class="form-control" name="prenom" placeholder="Entrez le prénom" required />
                    </div>
                    <div class="mb-3">
                      <label for="email" class="">Email GEI:</label>
                      <input type="email" id="email" class="form-control" name="email" placeholder="Entrez l'email" required />
                    </div>
                    <div class="mb-3">
                      <label for="telephone" class="">Numéro Téléphone GEI:</label>
                      <input type="tel" id="telephone" class="form-control" name="telephone" placeholder="Entrez le téléphone" required />
                    </div>
                    <div class="mb-3">
                      <label for="poste">Poste:</label>
                      <select id="poste" class="form-control" name="poste" required>
                        <option value="">Choisissez</option>
                        <option value="ELECTRO">ELECTRO</option>
                        <option value="SBE">SBE</option>
                      </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tableau des directions -->
        <div class="row">
          <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <p class="card-title">Liste de Directions</p>
                <div class="mb-3">
    <input
        type="text"
        id="searchInput"
        class="form-control"
        placeholder="Rechercher par Nom, Prénom, Email ou Téléphone"
        onkeyup="filterTable()"
    />
</div>
                <div class="table-responsive">
                  <table id="categoryTable"  class="table  table-respensive" style="width:100%">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Nom et Prénom</th>
                        <th>Poste</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Poste</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($directions as $direction)
                        <tr>
                          <td>{{ $direction->id }}</td>
                          <td>{{ $direction->nom }} </td>
                          <td>{{ $direction->prenom }}</td>
                          <td>{{ $direction->email }}</td>
                          <td>{{ $direction->telephone }}</td>
                          <td>{{ $direction->poste }}</td>
                          <td>
                            <!-- Bouton Modifier -->
<!-- Bouton Modifier -->
<button 
    type="button" 
    class="btn btn-light btn-sm" 
    data-bs-toggle="modal" 
    data-bs-target="#editDirectionModal" 
    data-id="{{ $direction->id }}"
    data-nom="{{ $direction->nom }}"
    data-prenom="{{ $direction->prenom }}"
    data-email="{{ $direction->email }}"
    data-telephone="{{ $direction->telephone }}"
    data-poste="{{ $direction->poste }}"
>
    Modifier
</button>
<!-- Modale Modifier Direction -->
<div class="modal fade" id="editDirectionModal" tabindex="-1" aria-labelledby="editDirectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDirectionModalLabel">Modifier Direction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editDirectionForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editNom" class="form-label">Nom Prénom </label>
                        <input type="text" class="form-control" id="editNom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPrenom" class="form-label">Poste</label>
                        <input type="text" class="form-control" id="editPrenom" name="prenom" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTelephone" class="form-label">Téléphone</label>
                        <input type="tel" class="form-control" id="editTelephone" name="telephone" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPoste" class="form-label">Département</label>
                        <select class="form-control" id="editPoste" name="poste" required>
                            <option value="">Choisir</option>
                            <option value="ELECTRO">ELECTRO</option>
                            <option value="SBE">SBE</option>
                        </select>
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
                       
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editDirectionModal = document.getElementById('editDirectionModal');
        editDirectionModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Bouton qui a déclenché la modale
            const id = button.getAttribute('data-id');
            const nom = button.getAttribute('data-nom');
            const prenom = button.getAttribute('data-prenom');
            const email = button.getAttribute('data-email');
            const telephone = button.getAttribute('data-telephone');
            const poste = button.getAttribute('data-poste');

            // Remplir les champs de la modale
            editDirectionModal.querySelector('#editNom').value = nom;
            editDirectionModal.querySelector('#editPrenom').value = prenom;
            editDirectionModal.querySelector('#editEmail').value = email;
            editDirectionModal.querySelector('#editTelephone').value = telephone;
            editDirectionModal.querySelector('#editPoste').value = poste;

            // Mettre à jour l'action du formulaire
            const form = editDirectionModal.querySelector('#editDirectionForm');
            form.action = `/directions/${id}`; // Assurez-vous que cette route est correcte
        });
    });
</script>
<script>
function filterTable() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('#categoryTable tbody tr');

        rows.forEach(row => {
            const name = row.cells[1].innerText.toLowerCase();
            row.style.display = name.includes(searchInput) ? '' : 'none';
        });
    }

</script>


<!-- Formulaire de Suppression -->
                         <!--   <form action="{{ route('directions.destroy', $direction->id) }}" method="POST" style="display:inline;">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette direction ?');">Supprimer</button>
                            </form>-->
                          </td>
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
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>

  <!-- plugins:js -->
  @include('themes.js')
</body>

</html>

