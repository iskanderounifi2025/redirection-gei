<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title> Ajouter Équipe</title>
  @include('themes.style')


</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
  
@include('themes.header')
@include('themes.sideleft')






      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
      
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
    <h1 class="text-center mb-4 display-4 display-md-3 display-lg-2">Ajouter Équipe</h1>

    <!-- Alertes de succès et d'erreurs -->
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

    <div id="alertError" class="alert alert-danger d-none" role="alert">Erreur lors de l'ajout de l'équipe.</div>
    <div id="alertSuccess" class="alert alert-success d-none" role="alert">Équipe ajoutée avec succès !</div>

    <!-- Formulaire d'ajout de l'équipe -->
    <form id="teamForm" action="{{ route('equipe.store') }}" method="POST" class="row g-3 mb-4" enctype="multipart/form-data">
      @csrf
      <div class="col-md-6">
        <label for="nometprenom" class="form-label">Nom et Prénom:</label>
        <input type="text" id="nometprenom" name="nometprenom" class="form-control" placeholder="Nom de l'Équipe" required />
      </div>

      <div class="col-md-6">
        <label for="email" class="form-label">Email:</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Email" required />
      </div>

      <div class="col-md-6">
        <label for="username" class="form-label">Nom d'utilisateur:</label>
        <input type="text" id="username" name="username" class="form-control" placeholder="Nom d'utilisateur" required />
      </div>

      <div class="col-md-6">
        <label for="password" class="form-label">Mot de passe:</label>
        <div class="input-group">
          <input type="password" id="password" name="password" class="form-control" placeholder="Mot de passe" required />
          <button type="button" id="togglePassword" class="btn btn-outline-secondary">
            👁️
          </button>
        </div>
      </div>

      <div class="col-md-6">
        <label for="brand" class="form-label">Sélectionnez une Marque:</label>
        <select id="brand" name="marque_id" class="form-control" required>
        <option>Choisir Une marque</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
            </select>
        </select>
      </div>

      <div class="col-md-6">
        <label for="role" class="form-label">Sélectionnez le Rôle:</label>
        <select id="role" name="role" class="form-control" required>
          <option value="" disabled selected>Sélectionnez un Rôle</option>
          <option value="1">Admin</option>
          <option value="2">Team</option>
        </select>
      </div>

      <div class="col-md-6">
        <label for="image" class="form-label">Télécharger une Image:</label>
        <input type="file" id="image" name="image" accept="image/*" class="form-control" />
      </div>

      <div class="col-md-6">
        <div id="imagePreview" class="mt-4" style="display: none;">
          <img id="previewImage" alt="Aperçu" class="img-thumbnail mt-2" style="max-height: 100px; width: auto;" />
        </div>
      </div>
      <div class="col-mb-6">
<input type="hidden" name="id_team" value="{{ $team->id }}">
</div>

      <div class="col-md-12">
        <button type="submit" class="btn btn-primary">Ajouter</button>
      </div>
    </form>

    <script>
      // Script pour afficher l'aperçu de l'image
      const imageInput = document.getElementById('image');
      const imagePreview = document.getElementById('imagePreview');
      const previewImage = document.getElementById('previewImage');

      imageInput.addEventListener('change', function () {
        const file = imageInput.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function (e) {
            previewImage.src = e.target.result;
            imagePreview.style.display = 'block';
          };
          reader.readAsDataURL(file);
        }
      });

      // Script pour afficher/cacher le mot de passe
      const togglePassword = document.getElementById('togglePassword');
      const passwordInput = document.getElementById('password');

      togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        togglePassword.textContent = type === 'password' ? '👁️' : '🙈';
      });

      // Gestion de la soumission du formulaire
      const teamForm = document.getElementById('teamForm');
      teamForm.addEventListener('submit', function (e) {
        e.preventDefault();
        document.getElementById('alertError').classList.add('d-none');
        document.getElementById('alertSuccess').classList.add('d-none');

        teamForm.submit(); // Soumission normale si PHP est utilisé pour gérer le formulaire
      });
    </script>
  </div>
</div>


          
<!-- -->

@include('themes.js')

        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>   
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
 <!-- plugins:js -->

  

<!-- End custom js for this page-->
</body>

</body>

</html>

