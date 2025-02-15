<!DOCTYPE html>
<html lang="fr-TN">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Modifier le profil</title>
  @include('themes.style')
</head>

<body>
  <div class="container-scroller">
    @include('themes.header')
    @include('themes.sideleft')

    <div class="row">
      <div class="col-md-12 stretch-card grid-margin">
        <div class="card">
          <div class="card-body">
            <div class="container mx-auto p-4">
              <h1 class="text-center mb-4 display-4 display-md-3 display-lg-2">Modifier votre profil</h1>

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

              <!-- Formulaire de mise à jour du profil -->
              <form id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3 mb-4">
                  <!-- Nom et prénom -->
                  <div class="col-md-6">
                    <label for="nometprenom" class="form-label">Nom et Prénom :</label>
                    <input type="text" id="nometprenom" name="nometprenom" class="form-control"
                      value="{{ $team->nometprenom }}" required>
                  </div>

                  <!-- Email -->
                  <div class="col-md-6">
                    <label for="email" class="form-label">Email :</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ $team->email }}" required>
                  </div>

                  <!-- Nom d'utilisateur -->
                  <div class="col-md-6">
                    <label for="username" class="form-label">Nom d'utilisateur :</label>
                    <input type="text" id="username" name="username" class="form-control"
                      value="{{ $team->username }}" required>
                  </div>

                  <!-- Mot de passe -->
                  <div class="col-md-6">
                    <label for="password" class="form-label">Nouveau mot de passe :</label>
                    <div class="input-group">
                      <input type="password" id="password" name="password" class="form-control"
                        placeholder="Laissez vide pour ne pas changer">
                      <button type="button" id="togglePassword" class="btn">👁️</button>
                    </div>
                  </div>

                  <!-- Téléchargement de l'image -->
                  <div class="col-md-6">
                  <label for="image" class="form-label">Télécharger une nouvelle image :</label>
  <input type="file" id="image" name="image" accept="image/*" class="form-control">
                  </div>

                  <!-- Aperçu de l'image -->
                  <div class="col-md-6">
                  <div id="imagePreview" class="mt-4" style="display: none;">
                  <img id="previewImage" alt="Aperçu" class="img-thumbnail mt-2" style="max-height: 100px; width: auto;">
</div>
                  </div>

                  <!-- Bouton de soumission -->
                  <div class="col-md-12">
                    <button type="submit" class="btn btn-primary mt-3">Mettre à jour</button>
                  </div>
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
              </script>
            </div>
          </div>
        </div>
      </div>
    </div>

    @include('themes.js')
  </div>
</body>

</html>
