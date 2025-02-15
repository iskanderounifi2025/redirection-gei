<!DOCTYPE html>
<html lang="fr">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Login</title>
  @include('themes.style')

  <link rel="shortcut icon" href="images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo" style="margin: 0 auto;  
    text-align: center;  
    max-width:200px;">
                <img src="images/logo.svg" alt="logo" style=" width: 100%;
    height: auto;">
              </div>
              <h4>Bonjour ! Commençons</h4>
              <h6 class="font-weight-light">Connectez-vous pour continuer.</h6>
              <form class="pt-3" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg" id="email" name="email" placeholder="Nom d'utilisateur" required value="{{ old('email') }}">
                  @error('email')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Mot de passe" required>
                    <div class="input-group-append">
                      <button type="button" class="btn form-control-lg" id="toggle-password">
                        <i class="fa fa-eye" id="toggle-icon"></i> 
                      </button>
                    </div>
                  </div>
                  @error('password')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
                @if(session('error'))
                  <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <div class="mt-3">
                  <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Se connecter</button>
                </div>
                <div class="my-2 d-flex justify-content-between align-items-center">
                  <a href="{{ url('password-oublier') }}" class="auth-link text-black">Mot de passe oublié ?</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="../../vendors/js/vendor.bundle.base.js"></script>
  @include('themes.js')

  <!-- Ajout du script pour afficher/masquer le mot de passe -->
  <script>
    document.getElementById('toggle-password').addEventListener('click', function () {
      var passwordField = document.getElementById('password');
      var toggleIcon = document.getElementById('toggle-icon');

      if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
      } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
      }
    });
  </script>
  <!-- endinject -->
</body>

</html>
