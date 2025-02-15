<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Mot de passe oublié </title>
  @include('themes.style')
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
               <h6 class="font-weight-light">Mot de passe oublié ? Veuillez indiquer votre email. ?
              </h6>
             
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

              <form class="pt-3" action="{{ route('password.send') }}" method="POST">
        
                
                <div class="form-group">
                <input type="email" id="email" name="email" class="form-control form-control-lg"  placeholder="
Votre email…
"  name="email" required>
                </div>
             
               
               
                <div class="mt-3">
  <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Envoyer</button>
                 </div>
                <div class="text-center mt-4 font-weight-light">
                Vous avez déjà un compte ?<a href="{{url('login')}}" class="text-primary">Retour</a>
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
   @include('themes.js')
  <!-- endinject -->
</body>

</html>
