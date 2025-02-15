<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Ajouter Événement</title>
  <!-- plugins:css -->
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

        

        <!-- Liste des événements -->
        <div class="row">
          <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <p class="card-title">Liste des Événements</p>
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
                <div class="col-md-12">
                      <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un produit...">
                    </div>
                  <table id="revendeursTable" class="table table-responsive" style="width:100%">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Nom de l'Événement</th>
                        <th>Date de Début</th>
                        <th>Date de Fin</th>
                        <th>Marque</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($evenements as $evenement)
                        <tr>
                          <td>{{ $evenement->id }}</td>
                          <td>{{ $evenement->nom }}</td>
                          <td>{{ $evenement->date_debut }}</td>
                          <td>{{ $evenement->date_fin }}</td>
                          <td>{{ $evenement->brand->name ?? 'Aucune marque' }}</td>
                          <td>
                            <!-- Bouton Modifier -->
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal"
                            data-id="{{ $evenement->id }}"
                            data-nom="{{ $evenement->nom }}"
                            data-date_debut="{{ $evenement->date_debut }}"
                            data-date_fin="{{ $evenement->date_fin }}"
                            data-brand_id="{{ $evenement->brand_id }}">
                              Modifier
                            </button>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                <div class="d-flex justify-content-center mt-4">
                 {{ $evenements->links('pagination::bootstrap-5') }}

                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Modal Modifier Événement -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Modifier l'Événement</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editEventForm" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
              <label for="nom">Nom de l'Événement</label>
              <input type="text" name="nom" id="nom" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="date_debut">Date de Début</label>
              <input type="datetime-local" name="date_debut" id="date_debut" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="date_fin">Date de Fin</label>
              <input type="datetime-local" name="date_fin" id="date_fin" class="form-control" required>
            </div>
            <input type="hidden" name="brand_id" id="brand_id">
            
            <button type="submit" class="btn btn-primary mt-3">Mettre à jour</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- JS files -->
  @include('themes.js')

  <script>
    // JavaScript pour pré-remplir le modal
    document.addEventListener('DOMContentLoaded', function () {
      $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Le bouton qui a ouvert le modal
        var id = button.data('id'); // Récupérer l'ID
        var nom = button.data('nom'); // Récupérer le nom
        var date_debut = button.data('date_debut'); // Récupérer la date de début
        var date_fin = button.data('date_fin'); // Récupérer la date de fin
        var brand_id = button.data('brand_id'); // Récupérer l'ID de la marque

        // Remplir les champs du formulaire
        var modal = $(this);
        modal.find('#nom').val(nom);
        modal.find('#date_debut').val(date_debut);
        modal.find('#date_fin').val(date_fin);
        modal.find('#brand_id').val(brand_id); // Remplir le champ caché pour le brand_id
        modal.find('form').attr('action', '/evenements/' + id); // Définir l'URL d'action pour le formulaire
      });
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script pour la recherche -->
<script>
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
 
@include('themes.style')

</body>
</html>
