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
              <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Gestion des Événements</h3>
              </div>
            </div>
          </div>
        </div>

        <!-- Formulaire d'ajout d'un événement -->
        <div class="row">
          <div class="col-md-12 stretch-card grid-margin">
            <div class="card">
              <div class="card-body">
                <div class="container mx-auto p-4">
                  <h1 class="text-center mb-4">Ajouter un Nouvel Événement</h1>

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

                  <form action="{{ route('ajouterevenement.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf <!-- Token CSRF pour la sécurité -->
                    <div class="mb-3">
                      <label for="nom" class="">Nom Événement :</label>
                      <input type="text" id="nom" class="form-control" name="nom" placeholder="Entrez Nom Événement" required />
                    </div>
                    <div class="mb-3">
                      <label for="date_debut" class="">Date de début :</label>
                      <input type="datetime-local" id="date_debut" class="form-control" name="date_debut" required />
                    </div>
                    <div class="mb-3">
                      <label for="date_fin" class="">Date de Fin :</label>
                      <input type="datetime-local" id="date_fin" class="form-control" name="date_fin" required />
                    </div>

                    <div class="mb-3">
                      <label for="brand_id">Choisir Une Marque :</label>
                      <select class="form-control" name="brand_id" required>
                        <option>Choisir Une marque</option>
                        @foreach ($brands as $brand)
                          <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-mb-6">
<input type="hidden" name="id_team" value="{{ $team->id }}">
</div>
                    <div class="mb-3">
                      <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Liste des événements -->
     


        </div>

      </div>
    </div>
  </div>

 <!-- Modal Modifier Événement -->
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
          <div class="form-group">
            <label for="brand_id">Marque</label>
            <select name="brand_id" id="brand_id" class="form-control" required>
              @foreach ($brands as $brand)
                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
              @endforeach
            </select>
          </div>
          <button type="submit" class="btn btn-primary mt-3">Mettre à jour</button>
        </form>
      </div>
    </div>
  </div>
</div>

  @include('themes.js')

  <script>
    function loadEventData(id) {
      fetch(`/evenements/${id}/edit`)
        .then(response => response.json())
        .then(data => {
          // Remplir le formulaire de la modale avec les données de l'événement
          document.getElementById('editEventForm').action = `/evenements/${data.id}`;
          document.getElementById('nom').value = data.nom;
          document.getElementById('date_debut').value = data.date_debut;
          document.getElementById('date_fin').value = data.date_fin;
          document.getElementById('brand_id').value = data.brand_id;

          // Afficher la modale
          $('#editModal').modal('show');
        })
        .catch(error => {
          console.error('Erreur:', error);
        });
    }
  </script>


<script>
 // JavaScript pour pré-remplir le modal
document.addEventListener('DOMContentLoaded', function () {
  // Quand le modal est ouvert
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
    modal.find('#brand_id').val(brand_id);
    modal.find('form').attr('action', '/evenements/' + id); // Définir l'URL d'action pour le formulaire
  });
});


    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
