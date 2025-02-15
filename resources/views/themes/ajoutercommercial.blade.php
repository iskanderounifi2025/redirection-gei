<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Commercial</title>
  <!-- plugins:css -->
  @include('themes.style')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>

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



        <!-- Formulaire d'ajout d'un commercial -->
        
<div class="row">
    <div class="col-md-12 stretch-card grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="container mx-auto p-4">
                    <h1 class="text-center mb-4">Commercial</h1>

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

                    <form action="{{ route('commercials.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="commercialName" class="form-label">Nom et prénom:</label>
                            <input type="text" id="commercialName" class="form-control" name="nomprenom" placeholder="Entrez Nom et prénom" required />
                        </div>
                        <div class="mb-3">
                            <label for="commercialEmail" class="form-label">Email GEI:</label>
                            <input type="email" id="commercialEmail" class="form-control" name="email" placeholder="Entrez E-mail GEI" required />
                        </div>
                        <div class="mb-3">
                            <label for="commercialPhone" class="form-label">Numéro Téléphone GEI:</label>
                            <input type="tel" id="commercialPhone" class="form-control" name="telephone" placeholder="Entrez Numéro Téléphone GEI" required />
                        </div>
                        <div class="mb-3">
                            <label for="commercialImage" class="form-label">Image:</label>
                            <input type="file" id="commercialImage" accept="image/*" name="image" class="form-control" required />
                            <div id="logoPreview" class="mt-2" style="display: none;">
                                <img id="logoImage" alt="Image Preview" class="img-thumbnail" style="max-width: 100px;" />
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="logo" class="form-label">Choisir Direction:</label>
                            <select name="direction_id" id="brandSelect" class="form-control" multiple required>
                                <option value="">Choisir une Direction</option>
                                @foreach ($directions as $direction)
                                    <option value="{{ $direction->id }}">{{ $direction->nom }}</option>
                                @endforeach
                            </select>  
                        </div>

                        <div class="col-mb-12">
                            <input type="hidden" name="id_team" value="{{ $team->id }}">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                    </form>

                    <script>
                        const commercialImageInput = document.getElementById('commercialImage');
                        const logoPreview = document.getElementById('logoPreview');
                        const logoImage = document.getElementById('logoImage');

                        commercialImageInput.addEventListener('change', function () {
                            const file = commercialImageInput.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = function (e) {
                                    logoImage.src = e.target.result;
                                    logoPreview.style.display = 'block';
                                }
                                reader.readAsDataURL(file);
                            }
                        });
                    </script>
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
              <p class="card-title">Liste de Commerciaux</p>
              <button id="exportExcelBtn" class="btn btn-light btn-sm">Exporter en Excel</button>

              <div class="col-md-12 text-right">
            <input type="text" id="searchCommercial" class="form-control" placeholder="Rechercher par nom, email, etc." onkeyup="searchTable()">
        </div>
                <div class="table-responsive">
          <!-- Le tableau affichant les commerciaux -->
<table id="commercialsTable" class="responsive-table" style="width:100%">
    <thead>
        <tr>
            <th></th>
            <th>Nom et Prénom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($commercials as $commercial)
            <tr>
                 <td>
                    @if ($commercial->image)
                        <img src="{{ asset('storage/'.$commercial->image) }}" alt="Image de {{ $commercial->nomprenom }}" width="50" class="img-sm rounded-circle">
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $commercial->nomprenom }}</td>
                <td>
    <a href="mailto:{{ $commercial->email }}" class="btn btn-light btn-sm">{{ $commercial->email }}</a>
</td>
<td>
    <a href="tel:{{ $commercial->telephone }}" class="btn btn-light btn-sm">{{ $commercial->telephone }}</a>
</td>

                <td>
                    <!-- Bouton Modifier - Ouverture de la modal -->
                    <button class="btn btn-light btn-sm" data-toggle="modal" data-target="#editCommercialModal" data-id="{{ $commercial->id }}">
    <i class="fa fa-edit"></i>
</button>                </td>


<!-- Ajoutez le script jQuery pour peupler la modal avec les données du commercial -->
<script>
 $(document).on('click', '[data-toggle="modal"]', function() {
    var commercialId = $(this).data('id');
    
    // Mettre à jour l'action du formulaire pour inclure l'ID du commercial
    var formAction = '/commercials/' + commercialId;
    $('#editCommercialForm').attr('action', formAction);

    // Récupérer les données du commercial via AJAX
    $.get('/commercials/' + commercialId + '/edit', function(data) {
        // Remplir les champs de la modal avec les données récupérées
        $('#commercialId').val(data.id);
        $('#nomprenom').val(data.nomprenom);
        $('#email').val(data.email);
        $('#telephone').val(data.telephone);
        $('#direction_id').val(data.direction_id);
        $('#id_team').val(data.id_team);
    });
});

</script>
   
                    <!-- Formulaire de Suppression -->
                   <!-- <form action="{{ route('commercials.destroy', $commercial->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce commercial?');">Supprimer</button>
                    </form>-->
            </tr>
        @endforeach
    </tbody>
</table>


<div class="modal fade" id="editCommercialModal" tabindex="-1" role="dialog" aria-labelledby="editCommercialModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCommercialModalLabel">Modifier Commercial</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulaire de modification -->
                <form id="editCommercialForm" method="POST" action="{{ route('commercials.update', $commercial->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <input type="hidden" id="commercialId" name="commercialId" value="{{ $commercial->id }}">

    <div class="form-group">
        <label for="nomprenom">Nom et Prénom</label>
        <input type="text" class="form-control" id="nomprenom" name="nomprenom" value="{{ $commercial->nomprenom }}">
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ $commercial->email }}">
    </div>

    <div class="form-group">
        <label for="telephone">Téléphone</label>
        <input type="number" class="form-control" id="telephone" name="telephone" value="{{ $commercial->telephone }}">
    </div>

    <div class="form-group">
        <label for="image">Image</label>

        <!-- Affichage de l'image actuelle si elle existe -->
        @if($commercial->image)
    <div class="mb-2">
        <img src="{{ asset('storage/'.$commercial->image) }}" alt="Image de {{ $commercial->nomprenom }}" width="100" class="img-thumbnail">
    </div>
@else
    <p>Aucune image actuelle</p>
@endif


        <!-- Champ d'upload de la nouvelle image -->
        <input type="file" accept="image/*" class="form-control" id="image" name="image">
    </div>

    <div class="form-group">
        <label for="direction_id">Direction</label>
        <select class="form-control" id="direction_id" name="direction_id">
            @foreach ($directions as $direction)
                <option value="{{ $direction->id }}" @if($commercial->direction_id == $direction->id) selected @endif>
                    {{ $direction->nom }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-mb-12">
<input type="hidden" name="id_team" value="{{ $team->id }}">
</div>


    <button type="submit" class="btn btn-primary">Enregistrer</button>
</form>

            </div>
            
        </div>
    </div>
</div>


<script>
    function searchTable() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchCommercial");
    filter = input.value.toUpperCase();
    table = document.getElementById("commercialsTable");
    tr = table.getElementsByTagName("tr");

    // Parcours des lignes du tableau
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td");
        if (td.length > 0) {
            let matchFound = false;
            // Vérifier toutes les colonnes du tableau
            for (let j = 0; j < td.length; j++) {
                txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    matchFound = true;
                    break;
                }
            }
            tr[i].style.display = matchFound ? "" : "none";
        }
    }
}

</script>     

<script>
// Ajouter un événement au bouton d'exportation Excel
document.getElementById("exportExcelBtn").addEventListener("click", function() {
    var table = document.getElementById("commercialsTable");
    
    // Utiliser la fonction SheetJS pour convertir le tableau HTML en un fichier Excel
    var wb = XLSX.utils.table_to_book(table, {sheet: "Commercials"});
    
    // Télécharger le fichier Excel
    XLSX.writeFile(wb, "commercials.xlsx");
});
</script>
<!-- Modal pour modifier un commercial -->


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
