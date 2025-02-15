<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="{{url('dashbaord')}}"><img src="images/logo.svg" class="mr-2" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="{{url('dashbaord')}}"><img src="images/logo-mini.svg" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button>
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item nav-search d-none d-lg-block">
            <div class="input-group">
            
             </div>
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
  <!-- Notifications Dropdown -->
  <li class="nav-item dropdown">
    <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
      <i class="ti-comment mx-0"></i>
      <span class="fb-notification-count">1</span>
      </a>

  </li>

  <!-- Messages Dropdown -->
  <li class="nav-item dropdown">
    <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-toggle="dropdown">
        <i class="ti-bell mx-0"></i>
        @if(session('success') || session('error'))
        <span class="fb-notification-badge position-absolute top-0 start-100 translate-middle bg-danger text-white">
    <span class="fb-notification-count">1</span>
</span>

</span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-right navbar-dropdown shadow-sm p-3" aria-labelledby="messageDropdown" style="min-width: 350px;">
        <h6 class="dropdown-header text-center">Notifications</h6>
        @if(session('success'))
            <div class="dropdown-item d-flex align-items-start mb-2">
                <div class="icon bg-success text-white rounded-circle p-2 mr-3">
                    <i class="ti-check"></i>
                </div>
                <div class="flex-grow-1">
                    <p class="mb-0 font-weight-bold">Operation Successful</p>
                    <p class="mb-0 small text-muted">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="dropdown-item d-flex align-items-start mb-2">
                <div class="icon bg-danger text-white rounded-circle p-2 mr-3">
                    <i class="ti-alert"></i>
                </div>
                <div class="flex-grow-1">
                    <p class="mb-0 font-weight-bold">Operation Failed</p>
                    <p class="mb-0 small text-muted">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <div class="dropdown-footer text-center mt-2">
            <a href="#" class="btn btn-outline-primary btn-sm">View All Notifications</a>
        </div>
    </div>
</li>





  <li class="nav-item dropdown">
  <h3>
    @if(Auth::check() && isset($team))
      <a href="{{ url('dashboard') }}" class="font-weight-bold-grey" title="{{ $team->nometprenom }}">
      <i class="fas fa-circle text-success"></i>      </a>
    @else
    <i class="fas fa-circle text-danger"></i>    
    
    @endif
  </h3>
  </li>


  <!-- Profile Dropdown -->
  <li class="nav-item nav-profile dropdown">
  <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
    <img src="{{ asset('storage/'.$team->image) }}" title="{{ $team->nometprenom }}" width="20"/>
  </a>

  <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
    <a class="dropdown-item" href="{{ url('modifier') }}">
      <i class="ti-pencil
 text-primary"></i> Modifier
    </a>

    <!-- Logout Form (using POST method) -->
    <form action="{{ route('logout') }}" method="POST" id="logoutForm" style="display: none;">
      @csrf
    </form>

    <a class="dropdown-item" href="javascript:void(0)" onclick="document.getElementById('logoutForm').submit();">
      <i class="ti-power-off text-primary"></i> Déconnexion
    </a>
  </div>
</li>

</ul>

        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      <div class="theme-setting-wrapper">
        <div id="settings-trigger"><i class="ti-settings"></i></div>
        <div id="theme-settings" class="settings-panel">
          <i class="settings-close ti-close"></i>
          <p class="settings-heading">SIDEBAR SKINS</p>
          <div class="sidebar-bg-options selected" id="sidebar-light-theme"><div class="img-ss rounded-circle bg-light border mr-3"></div>Light</div>
          <div class="sidebar-bg-options" id="sidebar-dark-theme"><div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark</div>
          <p class="settings-heading mt-2">HEADER SKINS</p>
          <div class="color-tiles mx-0 px-4">
            <div class="tiles success"></div>
            <div class="tiles warning"></div>
            <div class="tiles danger"></div>
            <div class="tiles info"></div>
            <div class="tiles dark"></div>
            <div class="tiles default"></div>
          </div>
        </div>
      </div>
 
      <script>
document.addEventListener('DOMContentLoaded', () => {
    // Vérifiez si une session "success" existe
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 3000
        });
    @endif

    // Vérifiez si une session "error" existe
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            showConfirmButton: false,
            timer: 3000
        });
    @endif
});

</script>
<style>
 .fb-notification-badge {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    font-size: 0.75rem; /* Taille compacte pour le texte */
    font-weight: bold;
    border-radius: 50%; /* Forme circulaire */
    border: 2px solid white; /* Bordure blanche */
    transform: translate(-50%, -50%);
}

.fb-notification-count {
    font-family: 'Arial', sans-serif;
    line-height: 1;
    color: #fff; /* Blanc sur rouge */
}


  </style>