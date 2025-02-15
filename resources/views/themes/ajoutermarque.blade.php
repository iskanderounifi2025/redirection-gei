<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Ajouter une Marque
  </title>
  <!-- plugins:css -->
  @include('themes.style')
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


        <div class="row">
          <div class="col-md-12 stretch-card grid-margin">
            <div class="card">
              <div class="card-body">
                <div class="container mx-auto p-4">
                  <h4 class="card-title">Ajouter une Marque</h4>

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


                  <!-- Formulaire d'ajout manuel -->
                  <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
    @csrf <!-- Token CSRF pour la sécurité -->
    <div class="mb-3">

    <input type="hidden" name="id_team" value="{{ $team->id }}">
    </div>

                     <div class="mb-3">

                      <label for="brandName" class="form-label">Nom de Marque:</label>
                      <input type="text" id="brandName" class="form-control" name="name" placeholder="Entrez le nom de la marque" required />
                    </div>
                
                    <div class="mb-3">
                      <label for="brandLogo" class="form-label">Logo de Marque:</label>
                      <input type="file" id="brandLogo" accept="image/*" name="logo" class="form-control" required />
                      <div id="logoPreview" class="mt-2" style="display: none;">
                        <img id="logoImage" alt="Brand Logo" class="img-thumbnail" style="max-width: 100px;" />
                      </div>
                    </div>
                    <div class="mb-3">
    <label for="commercial_id" class="form-label">Choisir Commercial:</label>
    <select name="commercial_id" id="commercial_id" class="form-control" required>
        <option value="">Choisir Commercial</option>
        @foreach ($commercials as $commercial)
            <option value="{{ $commercial->id }}">{{ $commercial->nomprenom }}</option>
        @endforeach
    </select>
</div>



                    <!-- Sélection des familles -->
                    <!-- <div class="mb-3">
                      <label for="familleSelect" class="form-label">Familles:</label>
                      <select multiple id="familleSelect" class="form-control" name="" required>
                        <option value="famille1">Famille 1</option>
                        <option value="famille2">Famille 2</option>
                        <option value="famille3">Famille 3</option>
                      </select>
                      <small class="form-text text-muted">(Utilisez Ctrl ou Cmd pour sélectionner plusieurs familles)</small>
                    </div>
 -->
                    <button type="submit" class="btn btn-primary w-100">Ajouter Marque Manuellement</button>
                  </form>

                  <!-- Formulaire d'import CSV -->
                 

                <script>
                  // Script pour gérer l'affichage des alertes et de l'aperçu de l'image
                  const brandLogoInput = document.getElementById('brandLogo');
                  const logoPreview = document.getElementById('logoPreview');
                  const logoImage = document.getElementById('logoImage');

                  brandLogoInput.addEventListener('change', function () {
                    const file = brandLogoInput.files[0];
                    if (file) {
                      const reader = new FileReader();
                      reader.onload = function (e) {
                        logoImage.src = e.target.result;
                        logoPreview.style.display = 'block';
                      }
                      reader.readAsDataURL(file);
                    }
                  });

                  const manualForm = document.getElementById('manualForm');
                  manualForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    // Préparation des données à envoyer au backend
                    const formData = new FormData(manualForm);
                    
                    fetch('/api/brands', {
                      method: 'POST',
                      body: formData,
                    })
                    .then(response => {
                      if (response.ok) {
                        document.getElementById('alertSuccess').classList.remove('d-none');
                        manualForm.reset();
                        logoPreview.style.display = 'none';
                      } else {
                        throw new Error('Erreur lors de l\'ajout de la marque.');
                      }
                    })
                    .catch(error => {
                      document.getElementById('alertError').classList.remove('d-none');
                    });
                  });

                  const csvForm = document.getElementById('csvForm');
                  csvForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const brandList = document.getElementById('importedBrandsList');
                    brandList.innerHTML = ''; // Réinitialiser la liste

                    // Exemple d'importation de marques
                    brandList.innerHTML += '<li class="list-group-item">Marque 1</li>'; // Exemple
                    brandList.innerHTML += '<li class="list-group-item">Marque 2</li>'; // Exemple
                    document.getElementById('importedBrandsContainer').style.display = 'block';
                  });
                </script>
              </div>
            </div>
          </div>
        </div>
 


         
        <!-- content-wrapper ends -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>

  <!-- plugins:js -->
  @include('themes.js')
</body>

</html>
