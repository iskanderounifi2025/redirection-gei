<!DOCTYPE html>
<html lang="fr">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Liste des Revendeurs</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>

  <!-- plugins:css -->
  @include('themes.style')
</head>

<body>
  <div class="container-scroller">
    @include('themes.header')
    @include('themes.sideleft')

    <!-- partial -->
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
          <div class="col-md-12 grid-margin">
            <div class="row">
              <div class="col-12 col-md-8 mb-4 mb-md-0">
              </div>
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

        <!-- Table des Revendeurs -->
        <div class="row">
          <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                
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
                <p class="card-title">Liste des Revendeurs</p>

                <!-- Search Box -->
                <input type="text" id="searchInput" class="form-control mb-3" placeholder="Rechercher un revendeur..." onkeyup="searchTable()">

                <div class="table-responsive">
                <div class="d-flex justify-content-between mb-3">
    <div>
    <button id="exportExcelBtn" class="btn btn-light btn-sm">Exporter en Excel</button>

    </div>
</div>

                  <table id="revendeursTable" class="table table-respensive" style="width:100%;">
                    <thead>
                      <tr>
                        <th>Logo & Nom</th>
                        <th>Commercial</th>
                        <th>E-mail</th>
                        <th>Numéro Téléphone</th>
                        <th>Id</th>

                        <th>Actions</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>

                      </tr>
                    </thead>
                    <tbody>
                      @foreach($revendeurs as $revendeur)
                      <tr>
                        <td>
                          <img src="{{ asset('storage/'. $revendeur->logo) }}" alt="Image de {{ $revendeur->name }}" style="width: 30px; height: 30px; margin-right: 10px;">
                          {{ $revendeur->name }}
                        </td>
                        <td>
                @if($revendeur->commercials)
                    {{ $revendeur->commercials->nomprenom }} <!-- Affichage du nom du commercial -->
                @else
                    Aucune attribution
                @endif
            </td>
                        <td><a href="mailto:{{ $revendeur->email }}">{{ $revendeur->email }}</a></td>
                        <td><a href="tel:{{ $revendeur->telephone }}">{{ $revendeur->telephone }}</a></td>
                        <td> {{ $revendeur->id }}</td>

                        <td>
  <form action="{{ route('revendeurs.changeEtat', ['id' => $revendeur->id]) }}" method="POST" style="display:inline;">
    @csrf
    @method('PUT')
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" name="etat" role="switch" onchange="this.form.submit()" {{ $revendeur->etat == 1 ? 'checked' : '' }}>
      <label class="form-check-label">
        {{ $revendeur->etat == 1 ? 'Activer' : 'Désactiver' }}
      </label>
    </div>
  </form>
</td>

                        <td>
                          <!-- Bouton de détail -->
                          <button class="btn btn-light" data-toggle="modal" data-target="#detailModal{{ $revendeur->id }}">
                            <i class="ti-info"></i>  
                          </button>
</td>            
                          <!-- Toggle "Change" Button -->
                          <td>
        <button class="btn btn-light btn-sm edit-revendeur-btn" 
                data-id="{{ $revendeur->id }}" 
                data-name="{{ $revendeur->name }}" 
                data-email="{{ $revendeur->email }}" 
                data-email_2="{{ $revendeur->email_2 }}" 
                data-nometprenom="{{ $revendeur->nometprenom }}" 
                data-telephone="{{ $revendeur->telephone }}" 
                data-telephone_2="{{ $revendeur->telephone_2 }}" 
                data-address_red="{{ $revendeur->address_red }}"
                data-commercials="{{ $revendeur->commercials->nomprenom }}">
            <i class="ti-pencil text-danger"></i>
        </button>
    </td>
                       </tr>
      <!-- Modal pour modifier le revendeur -->
 <!-- Modal pour modifier le revendeur -->
 <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editRevendeurForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Modifier Revendeur</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="revendeur-id" name="id">
                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" class="form-control" id="name" name="name" >
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="email_2">Email 2</label>
                        <input type="email" class="form-control" id="email_2" name="email_2">
                    </div>
                    <div class="form-group">
                        <label for="nometprenom">Nom et Prénom</label>
                        <input type="text" class="form-control" id="nometprenom" name="nometprenom" >
                    </div>
                    <div class="form-group">
                        <label for="telephone">Téléphone</label>
                        <input type="text" class="form-control" id="telephone" name="telephone" >
                    </div>
                    <div class="form-group">
                        <label for="telephone_2">Téléphone 2</label>
                        <input type="text" class="form-control" id="telephone_2" name="telephone_2">
                    </div>
                    <div class="form-group">
                        <label for="address_red">Adresse</label>
                        <input type="text" class="form-control" id="address_red" name="address_red" required>
                    </div>
                    <div class="form-group">
    <label for="commercial_id">Liste de commerciaux</label>
    <select name="commercial_id" class="form-control" id="commercial_id">
        <!-- L'option sélectionnée par défaut est celle du commercial actuel du revendeur -->
        <option value="{{ $revendeur->commercials->id }}">
            {{ $revendeur->commercials->nomprenom }}
        </option>

        <!-- Liste des commerciaux disponibles -->
        @foreach($commercials as $commercial)
            <option value="{{ $commercial->id }}">{{ $commercial->nomprenom }}</option>
        @endforeach
    </select>
</div>





                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Sauvegarder</button>
                </div>
            </form>
        </div>
    </div>
</div>


                      <!-- Modal pour Détails -->
                      <div class="modal fade" id="detailModal{{ $revendeur->id }}" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="detailModalLabel">Détails du Revendeur - {{ $revendeur->name }}</h5>
                              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                              </button>      
                            </div>
                            <div class="modal-body">
                              <p><strong>Nom:</strong> {{ $revendeur->nometprenom }}</p>
                              <p><strong>Email 1:</strong> <a href="mailto:{{ $revendeur->email }}">{{ $revendeur->email }}</a></p>
                              	
                              <p><strong>Email 2:</strong>  <a href="mailto:{{ $revendeur->email_2 }}">{{ $revendeur->email_2 }}</a> </p>

                              <p><strong>Téléphone:</strong> <a href="tel:{{ $revendeur->telephone }}">{{ $revendeur->telephone }}</a></p>
                              <p><strong>Téléphone:</strong> <a href="tel:{{ $revendeur->telephone_2 }}">{{ $revendeur->telephone_2 }}</a></p>

                              <p><strong>Adresse :</strong> {{ $revendeur->address_red }}</p>
<p>
    <strong>Lien Google Maps :</strong>
    <a href="{{ $revendeur->address_red }}" target="_blank">
        Ouvrir dans Google Maps
    </a>
</p>

                              <p><strong>Remarque:</strong> {{ $revendeur->remarque }}</p>
                              <p><strong>Date d'ajout:</strong> {{ $revendeur->created_at->format('d M Y') }}</p>
                              <p><strong>Commercial:</strong> {{ $revendeur->commercials->nomprenom }}</p>
                            </div>
                          </div>
                        </div>
                      </div>


                      @endforeach
                    </tbody>
                  </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-container mt-3">
                  <button id="prevPage" class="btn btn-light" onclick="changePage(-1)">Précédent</button>
                  <button id="nextPage" class="btn btn-light" onclick="changePage(1)">Suivant</button>
                </div>

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
 

 


<!-- Script pour remplir la modal avec les données du revendeur -->
 
<script>
document.addEventListener('DOMContentLoaded', () => {
    const editButtons = document.querySelectorAll('.edit-revendeur-btn');
    const editForm = document.getElementById('editRevendeurForm');
    const modal = new bootstrap.Modal(document.getElementById('editModal'));

    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const email = button.getAttribute('data-email');
            const email2 = button.getAttribute('data-email_2');
            const nometprenom = button.getAttribute('data-nometprenom');
            const telephone = button.getAttribute('data-telephone');
            const telephone2 = button.getAttribute('data-telephone_2');
            const addressRed = button.getAttribute('data-address_red');

            // Remplir les champs du modal
            document.getElementById('revendeur-id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('email').value = email;
            document.getElementById('email_2').value = email2;
            document.getElementById('nometprenom').value = nometprenom;
            document.getElementById('telephone').value = telephone;
            document.getElementById('telephone_2').value = telephone2;
            document.getElementById('address_red').value = addressRed;

            // Mettre à jour l'URL d'action du formulaire
            editForm.action = `/revendeurs/${id}`;
            
            modal.show();
        });
    });
});

  </script>
 

<script>

  // Existing pagination and search functions
  let currentPage = 1;
  const rowsPerPage = 10;

  function changePage(direction) {
    currentPage += direction;
    renderTable();
  }

  function renderTable() {
    const rows = document.querySelectorAll('#revendeursTable tbody tr');
    const totalRows = rows.length;
    const totalPages = Math.ceil(totalRows / rowsPerPage);

    rows.forEach((row, index) => {
      const start = (currentPage - 1) * rowsPerPage;
      const end = start + rowsPerPage;
      row.style.display = (index >= start && index < end) ? '' : 'none';
    });

    // Update pagination buttons
    document.getElementById('prevPage').disabled = currentPage === 1;
    document.getElementById('nextPage').disabled = currentPage === totalPages;
  }

  // Search Function
  function searchTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const rows = document.querySelectorAll('#revendeursTable tbody tr');

    rows.forEach((row) => {
      const nameCell = row.cells[0];
      if (nameCell && nameCell.textContent.toLowerCase().includes(filter)) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  }

  // Initialize table
  renderTable();
</script>
<script>
// Ajouter un événement au bouton d'exportation Excel
document.getElementById("exportExcelBtn").addEventListener("click", function() {
    var table = document.getElementById("revendeursTable");
    
    // Utiliser la fonction SheetJS pour convertir le tableau HTML en un fichier Excel
    var wb = XLSX.utils.table_to_book(table, {sheet: "Revendeurs"});
    
    // Télécharger le fichier Excel
    XLSX.writeFile(wb, "revendeurs.xlsx");
});
</script>
@include('themes.js')

 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
