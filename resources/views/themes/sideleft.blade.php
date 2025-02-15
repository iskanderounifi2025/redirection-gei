
 
 @if(Auth::check())
                <!-- Récupérer l'utilisateur connecté -->
                @php $role = Auth::user()->role; @endphp

                <!-- Affichage spécifique pour admin -->
                @if($role == 2)

<nav class="sidebar sidebar-offcanvas vertical-icon-menu" id="sidebar">
    <ul class="nav">
        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="{{ url('dashboard') }}">
                <i class="ti-home menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <!-- Brand -->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#brands" aria-expanded="false" aria-controls="brands">
                <i class="icon-tag  menu-icon"></i>
                <span class="menu-title">Brand</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="brands">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ url('ajoutermarque') }}">Ajouter une Brand</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('listemarque') }}">Liste des Brands</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('marque') }}">Dashboard Brands </a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('dashboard_marque') }}">CA par Marque et Événement </a></li>
                 </ul>
            </div>
        </li>

        <!-- Produit -->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#products" aria-expanded="false" aria-controls="products">
                <i class="icon-box menu-icon"></i>
                <span class="menu-title">Produit</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="products">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ url('ajouterproduit') }}">Ajouter un Produit</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('listeproduit') }}">Liste des Produits</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{url('dashboard_produit')}}">Dashboard Produits</a></li>
                </ul>
            </div> 
        </li>

        <!-- Revendeur -->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#retailers" aria-expanded="false" aria-controls="retailers">
                <i class="ti-control-shuffle menu-icon"></i>
                <span class="menu-title">Revendeur</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="retailers">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ url('ajouterrevendeur') }}">Ajouter un Revendeur</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('listerevendeur') }}">Liste des Revendeurs</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('dashboard_revendeur') }}">Dashboard Revendeurs</a></li>
                </ul>
            </div>
        </li>

        <!-- Team -->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#teams" aria-expanded="false" aria-controls="teams">
                <i class="ti-user menu-icon"></i>
                <span class="menu-title">Team</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="teams">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ url('ajouterequipe') }}">Ajouter une Équipe</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('listeequipe') }}">Liste des Équipes</a></li>
                 </ul>
            </div>
        </li>

        <!-- Rédirection -->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#redirects" aria-expanded="false" aria-controls="redirects">
                <i class="ti-direction-alt menu-icon"></i>
                <span class="menu-title">Rédirection</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="redirects">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ url('ajouterredirection') }}">Ajouter Redirection</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('listeredirection') }}">Liste de Redirections</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('emails-redirections') }}">Liste de Redirections</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('dashboard_redirection') }}">Saison  Redirections Redirections</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('statut_redirection') }}">Dashboard Redirection</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('produitsproposer') }}">produits proposer</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('cartechaleur') }}">Carte Chaleur</a></li>

                </ul>
            </div>
        </li>

        <!-- Evenement -->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#events" aria-expanded="false" aria-controls="events">
                <i class="fa-regular fa-calendar-days menu-icon"></i>
                <span class="menu-title">Événement</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="events">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ url('ajouterevenement') }}">Ajouter Événement</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('listeevenement') }}">Liste des Événements</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('dashboard_evenement') }}">Dashboard Événement</a></li>
                </ul>
            </div>
        </li>

        <!-- marketing -->

        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#marketing" aria-expanded="false" aria-controls="marketing">
                <i class="ti-money menu-icon"></i>
                <span class="menu-title">Compagne</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="marketing">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ url('ajoutercompagne') }}">Cree Une Compagne</a></li>
                    <li class="nav-item"><a class="nav-link" href="listecompagne">Liste des Compagnes</a></li>
                </ul>
            </div>
        </li>

 

        <!-- Paramètre -->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#settings" aria-expanded="false" aria-controls="settings">
                <i class="ti-settings menu-icon"></i>
                <span class="menu-title">Paramètres</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="settings">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ url('ajoutercommercial') }}">Commercials</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('ajoutercategorie') }}">Catégories</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('ajouterdirection') }}">Directions</a></li>

                </ul>
            </div>
        </li>
      
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#sites" aria-expanded="false" aria-controls="settings">
                <i class="ti-world
 menu-icon"></i>
                <span class="menu-title">Sites</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="sites">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ url('sites') }}">Gestion des Sites</a></li>
                  </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#mail" aria-expanded="false" aria-controls="settings">
                <i class="ti-comment menu-icon"></i>
                <span class="menu-title">Mail</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="mail">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ url('config') }}">Configuration mail</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('ajoutercategorie') }}">Inbox</a></li>
                 </ul>
            </div>
        </li>
   <li class="nav-item">
            <a class="nav-link " href="{{ url('ajouterredirection') }}">
                <i class="ti-plus menu-icon"></i>
                <span class="menu-title">Ajouter Redirection</span>
            </a>
        </li>

        
     </ul>
</nav>

@elseif($role == 1)

<nav class="sidebar sidebar-offcanvas vertical-icon-menu" id="sidebar">
    <ul class="nav">
        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="{{ url('dashboard') }}">
                <i class="ti-home menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#dashboard_admin_menu"  aria-expanded="false" aria-controls="dashboard_admin_menu">
                <i class="ti-bar-chart menu-icon"></i>
                <span class="menu-title">Dashboards</span>
                <i class="menu-arrow"></i>
            </a>

            <div class="collapse" id="dashboard_admin_menu">
                <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="{{ url('dashboard_admin') }}"> Admin</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('dashboarddetail') }}"> Details</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('dashboard_tv') }}"> Tv</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('report') }}">Rapport  </a></li>

                 </ul>
            </div>


        </li>
        
        

        <!-- Brand -->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#brands" aria-expanded="false" aria-controls="brands">
                <i class="icon-tag  menu-icon"></i>
                <span class="menu-title">Brand</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="brands">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ url('ajoutermarque') }}">Ajouter une Brand</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('listemarque') }}">Liste des Brands</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('marque') }}">Dashboard Brands </a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('dashboard_marque') }}">CA par Marque et Événement </a></li>

                 </ul>
            </div>
        </li>

        <!-- Produit -->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#products" aria-expanded="false" aria-controls="products">
                <i class="icon-box menu-icon"></i>
                <span class="menu-title">Produit</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="products">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ url('ajouterproduit') }}">Ajouter un Produit</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('listeproduit') }}">Liste des Produits</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{url('dashboard_produit')}}">Dashboard Produits</a></li>
                </ul>
            </div> 
        </li>

        <!-- Revendeur -->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#retailers" aria-expanded="false" aria-controls="retailers">
                <i class="ti-control-shuffle menu-icon"></i>
                <span class="menu-title">Revendeur</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="retailers">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ url('ajouterrevendeur') }}">Ajouter un Revendeur</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('listerevendeur') }}">Liste des Revendeurs</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('dashboard_revendeur') }}">Dashboard Revendeurs</a></li>
                </ul>
            </div>
        </li>

        <!-- Team -->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#teams" aria-expanded="false" aria-controls="teams">
                <i class="ti-user menu-icon"></i>
                <span class="menu-title">Team</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="teams">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ url('ajouterequipe') }}">Ajouter une Équipe</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('listeequipe') }}">Liste des Équipes</a></li>
                 </ul>
            </div>
        </li>

        <!-- Rédirection -->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#redirects" aria-expanded="false" aria-controls="redirects">
                <i class="ti-direction-alt menu-icon"></i>
                <span class="menu-title">Rédirection</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="redirects">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ url('ajouterredirection') }}">Ajouter Redirection</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('listeredirection') }}">Liste de Redirections</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('emails-redirections') }}">Emails des Redirections</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('dashboard_redirection') }}">Saison  Redirections</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('statut_redirection') }}">Dashboard Redirection</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('produitsproposer') }}">produits proposer</a></li>

                    <li class="nav-item"><a class="nav-link" href="{{ url('cartechaleur') }}">Carte Chaleur</a></li>

                </ul>
            </div>
        </li>

        <!-- Evenement -->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#events" aria-expanded="false" aria-controls="events">
                <i class="fa-regular fa-calendar-days menu-icon"></i>
                <span class="menu-title">Événement</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="events">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ url('ajouterevenement') }}">Ajouter Événement</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('listeevenement') }}">Liste des Événements</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('dashboard_evenement') }}">Dashboard Événements</a></li>
                </ul>
            </div>
        </li>

        <!-- marketing -->

        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#marketing" aria-expanded="false" aria-controls="marketing">
                <i class="ti-money menu-icon"></i>
                <span class="menu-title">Compagne</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="marketing">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ url('ajoutercompagne') }}">Cree Une Compagne</a></li>
                    <li class="nav-item"><a class="nav-link" href="listecompagne">Liste des Compagnes</a></li>
                </ul>
            </div>
        </li>


        <!-- Direction -->
   

        <!-- Paramètre -->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#settings" aria-expanded="false" aria-controls="settings">
                <i class="ti-settings menu-icon"></i>
                <span class="menu-title">Paramètres</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="settings">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ url('ajoutercommercial') }}">Commercials</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('ajoutercategorie') }}">Catégories</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('ajouterdirection') }}">Directions</a></li>

                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#mail" aria-expanded="false" aria-controls="settings">
                <i class="ti-comment menu-icon"></i>
                <span class="menu-title">Mail</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="mail">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ url('config') }}">Configuration mail</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('inbox') }}">Inbox</a></li>
                 </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#sites" aria-expanded="false" aria-controls="settings">
                <i class="ti-world
 menu-icon"></i>
                <span class="menu-title">Sites</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="sites">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ url('sites') }}">Gestion des Sites</a></li>
                  </ul>
            </div>
        </li>
      

   <li class="nav-item">
            <a class="nav-link " href="{{ url('ajouterredirection') }}">
                <i class="ti-plus menu-icon"></i>
                <span class="menu-title">Ajouter Redirection</span>
            </a>
        </li>

       
     </ul>
</nav>


@else

<nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="{{ url('/') }}">Bienvenue</a>
        </nav>
        @endif
        @else
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="{{ url('/') }}">Bienvenue</a>
        </nav>
        @endif
