<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Gestion des Équipes</title>
    @include('themes.style')

    <!-- Ajout de FontAwesome pour les icônes -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
            <div class="col-12 col-md-8 mb-4 mb-md-0">
               
            </div>
            <div class="col-12 col-md-4">
                <div class="d-flex justify-content-md-end">
                    <div>
                        <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" id="dropdownMenuDate2" aria-haspopup="true" aria-expanded="true">
                            <i class="ti-calendar
"></i> Aujourd'hui ({{ now()->format('d M Y') }} - {{ now()->format('H:i') }})
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
  <!-- Liste des équipes -->
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
                                <p class="card-title">Liste des Équipes</p>
                                 <!-- Search Box -->
                                 <input type="text" id="searchInput" class="form-control mb-3" placeholder="Rechercher un Team..." onkeyup="searchTable()">

                                <div class="table-responsive">
                                    <table id="revendeursTable" class="display expandable-table" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nom</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Marque</th>
                                                <th>Etat</th>
                                                <th>Action</th>
                                                <th></th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($equipes as $equipe)
                                            <tr>
                                            <td>
  <img src="{{ asset('storage/'.$equipe->image) }}" alt="Image de {{ $equipe->nometprenom }}" width="50" class="img-lg rounded">
</td>

                                                <td><a href="#">{{ $equipe->nometprenom }}</a></td>
                                                <td>{{ $equipe->email }}</td>
                                                <td>
                                                    @if($equipe->role == 1)
                                                        Admin
                                                    @elseif($equipe->role == 2)
                                                        Team
                                                    @else
                                                        Rôle inconnu
                                                    @endif
                                                </td>
                                                 <td>
            {{ $brands->firstWhere('id', $equipe->marque_id)?->name ?? 'Aucune marque' }}
                                                      </td>
                                                <td>
                                                    <!-- Activer/Désactiver -->
                                                    <form action="{{ route('equipes.toggleStatus', $equipe->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch" 
                                                                   id="flexSwitchCheck{{ $equipe->id }}" 
                                                                   {{ $equipe->etat == 'active' ? 'checked' : '' }} onchange="this.form.submit()">
                                                            
                                                        </div>
                                                    </form>
</td><td>
                                                    <!-- Détails de l'équipe -->
 
                                                    <!-- Modifier l'équipe -->
                                                    <button type="button" class="btn btn-icon btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $equipe->id }}"
                                                            data-id="{{ $equipe->id }}"
                                                            data-nometprenom="{{ $equipe->nometprenom }}"
                                                            data-email="{{ $equipe->email }}"
                                                            data-role="{{ $equipe->role }}"
                                                            data-marque_id="{{ $equipe->marque_id }}">
                                                        <i class="ti-pencil
"></i>
                                                    </button>

                                                    <!-- Supprimer l'équipe -->
                                                   <!-- <form action="{{ route('equipes.destroy', $equipe->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette équipe ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-icon btn-sm" type="submit"><i class="ti-trash
"></i></button>
                                                    </form>-->
                                                   <!-- Bouton avec icône pour ouvrir la modal -->
<button type="button" class="btn btn-icon btn-sm" data-bs-toggle="modal" data-bs-target="#modalAffecterMarque">
    <i class="fas fa-plus"></i> 
</button>
<!-- Modal -->
<div class="modal fade" id="modalAffecterMarque" tabindex="-1" aria-labelledby="modalAffecterMarqueLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAffecterMarqueLabel">Affecter une marque à l'équipe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulaire de sélection de la marque -->
                <form method="POST" action="{{ route('equipes.affecter-marque', $equipe->id) }}">
                    @csrf
                    <div class="form-group">
                        <label for="marque_id">Sélectionner une marque</label>
                        <select name="marque_id" class="form-control" required>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" {{ $equipe->marque_id == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Affecter la marque</button>
                </form>
            </div>
     
        </div>
    </div>
</div>

                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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
    </div>

    <!-- Modal Modifier Équipe -->
    @foreach ($equipes as $equipe)
    <div class="modal fade" id="editModal{{ $equipe->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $equipe->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel{{ $equipe->id }}">Modifier l'Équipe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('equipes.update', $equipe->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="nometprenom">Nom de l'Équipe</label>
                            <input type="text" name="nometprenom" id="nometprenom" class="form-control" value="{{ $equipe->nometprenom }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ $equipe->email }}" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Rôle</label>
                            <select name="role" id="role" class="form-control" required>
                                <option value="1" {{ $equipe->role == 1 ? 'selected' : '' }}>Admin</option>
                                <option value="2" {{ $equipe->role == 2 ? 'selected' : '' }}>Team</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="marque_id">Marque</label>
                            <select name="marque_id" id="marque_id" class="form-control" required>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ $equipe->marque_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Mettre à jour</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    @include('themes.js')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Quand le modal est ouvert
            $('#editModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Le bouton qui a ouvert le modal
                var id = button.data('id');
                var nometprenom = button.data('nometprenom');
                var email = button.data('email');
                var role = button.data('role');
                var marque_id = button.data('marque_id');

                // Remplir les champs du formulaire
                var modal = $(this);
                modal.find('#nometprenom').val(nometprenom);
                modal.find('#email').val(email);
                modal.find('#role').val(role);
                modal.find('#marque_id').val(marque_id);
                modal.find('form').attr('action', '/equipes/' + id); // Définir l'URL d'action pour le formulaire
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

 

  // Initialize table
  renderTable();
 
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function searchTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById('searchInput');
            filter = input.value.toUpperCase();
            table = document.getElementById("revendeursTable");
            tr = table.getElementsByTagName("tr");
            
            for (i = 1; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td");
                if (td) {
                    txtValue = "";
                    for (var j = 0; j < td.length; j++) {
                        if (td[j]) {
                            txtValue += td[j].textContent || td[j].innerText;
                        }
                    }
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }       
            }
        }
    </script>
</body>
</html>
