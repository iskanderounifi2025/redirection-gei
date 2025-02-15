<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title> Dashboard</title>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Fonction pour effectuer l'actualisation du contenu du body sans recharger la page entière
        function autoRefreshBody() {
            setInterval(function() {
                // Exemple d'Ajax pour récupérer tout le contenu de la page
                $.ajax({
                    url: 'votre-url-pour-recharger-le-body', // URL qui renvoie le contenu complet de la page
                    type: 'GET',
                    success: function(data) {
                        // Remplacer tout le contenu du body avec les nouvelles données
                        $('body').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error("Erreur lors de l'actualisation du contenu :", error);
                    }
                });
            }, 5000); // 600000 ms = 10 minutes
        }

        // Appeler la fonction dès le chargement de la page
        $(document).ready(function() {
            autoRefreshBody();
        });
    </script>

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
          <div class="col-lg-12 grid-margin stretch-card">
    <div class="card shadow-sm">
    <div class="card-body">
        <h4 class="card-title text-left">Les 30 Dernières Redirections</h4>
       
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-respensive">
                    <tr>
                        <th>Référence</th>
                        <th>Produits avec Prix</th>
                        <th>Client</th>
                        <th>Total Commande (TND)</th>
                        <th>Revendeur</th>
                        <th>Date Première Redirection</th>
                        <th>État</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($redirection as $item)
                    <tr>
                        <td>{{ $item->ref_redirection }}</td>
                        <td>
                            <details class="mb-3">
                                <summary class="btn btn-primary">Voir les produits</summary>
                                <ul>
                                    @foreach(explode(',', $item->produits_avec_prix) as $produit)
                                        <li>{{ $produit }}</li>
                                    @endforeach
                                </ul>
                            </details>
                        </td>
                        <td>{{ $item->client_nom }} {{ $item->client_prenom }}</td>
                        <td>{{ number_format($item->total_commande, 3) }} TND</td>
                        <td>{{ $item->revendeur_nom }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->date_premiere_redirection)->format('d M Y') }}</td>
                        <td>
                            @php
                            $btnClass = match($item->etat_redirection) {
                                'Annulé' => 'btn-danger',
                                'Envoyé au revendeur' => 'btn-warning',
                                'Validé, envoyé au client' => 'btn-success',
                                'Renvoyé' => 'btn-info',
                                default => 'btn-secondary',
                            };
                            $iconClass = match($item->etat_redirection) {
                                'Annulé' => 'fas fa-times-circle',
                                'Envoyé au revendeur' => 'fas fa-truck',
                                'Validé, envoyé au client' => 'fas fa-check-circle',
                                'Renvoyé' => 'fas fa-sync-alt',
                                default => 'fas fa-question-circle',
                            };
                            @endphp
                            <button class="btn {{ $btnClass }} text-white d-flex align-items-center">
                                <i class="{{ $iconClass }} me-2"></i> {{ $item->etat_redirection }}
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
       
</div>
</div> </div>
 </div>


           <div class="col-lg-4 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Brands Ranking YTD</h4>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Marques</th>
                            <th>Total CA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($chiffreAffairesYTD as $stat)
                            <tr>
                                <td>{{ $stat->marques }}</td>
                                <td>{{ number_format($stat->total_chiffre_affaires, 3, ',', ' ') }}TND</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
  <!-- Brands Ranking MTD -->
  <div class="col-lg-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Brands Ranking MTD</h4>
                <div class="table-responsive">
                    @if(empty($chiffreAffairesMTD))
                        <p>Aucune donnée disponible pour MTD.</p>
                    @else
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Marques</th>
                                    <th>Total CA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($chiffreAffairesMTD as $stat)
                                    <tr>
                                        <td>{{ $stat->marques }}</td>
                                        <td>{{ number_format($stat->total_chiffre_affaires, 3, ',', ' ') }}TND</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
           <!-- Brands Ranking DoD -->
    <div class="col-lg-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Brands Ranking DoD</h4>
                <div class="table-responsive">
                    @if(empty($chiffreAffairesDoD))
                        <p>Aucune donnée disponible pour DoD.</p>
                    @else
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Marques</th>
                                    <th>Total CA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($chiffreAffairesDoD as $stat)
                                    <tr>
                                        <td>{{ $stat->marques }}</td>
                                        <td>{{ number_format($stat->total_chiffre_affaires, 3, ',', ' ') }} TND</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
 

<div class="row">

   <div class="col-lg-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"> 10 SKU Ranking YTD</h4>
                <div class="table-responsive">
                    @if(empty($chiffreAffairesSKUYTD))
                        <p>Aucune donnée disponible pour YTD.</p>
                    @else
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Total CA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($chiffreAffairesSKUYTD as $stat)
                                     <tr>
                                        <td>{{ $stat->sku }}</td>
                                        <td>{{ number_format($stat->total_chiffre_affaires, 3, ',', ' ') }} TND</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>



<div class="col-lg-4 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">10 SKU Ranking DoD</h4>
            <div class="table-responsive">
                @if(empty($chiffreAffairesSKUDOD))
                    <p>Aucune donnée disponible pour DoD.</p>
                @else
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Total CA</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($chiffreAffairesSKUDOD as $stat)
                                <tr>
                                    <td>{{ $stat->sku }}</td>
                                    <td>{{ number_format($stat->total_chiffre_affaires, 3, ',', ' ') }} TND</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>


    <div class="col-lg-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">  10 SKU Ranking MTD</h4>
                <div class="table-responsive">
                    @if(empty($chiffreAffairesSKUMTD))
                        <p>Aucune donnée disponible pour MTD.</p>
                    @else
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Total CA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($chiffreAffairesSKUMTD as $stat)
                                    <tr>
                                        <td>{{ $stat->sku }}</td>
                                        <td>{{ number_format($stat->total_chiffre_affaires, 3, ',', ' ') }} TND</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
                      
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Revendeurs Ranking MTD</h4>
                <div class="table-responsive">
                    @if(empty($chiffresAffairesRev))
                        <p>Aucune donnée disponible pour MTD.</p>
                    @else
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                <tr>
                <th>Revendeur</th>
                <th>CA YTD (Année)</th>
                <th>CA MTD (Mois)</th>
                <th>CA DoD (Jour)</th>
            </tr>
                                </tr>
                            </thead>
                            <tbody>
            @foreach($chiffresAffairesRev as $item)
                <tr>
                    <td>{{ $item->revendeur_name }}</td>
                    <td>{{ number_format($item->chiffre_affaire_YTD, 3) }} TND</td>
                    <td>{{ number_format($item->chiffre_affaire_MTD, 3) }} TND</td>
                    <td>{{ number_format($item->chiffre_affaire_DoD, 3) }} TND</td>
                </tr>
            @endforeach
        </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
 
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>   
    <!-- page-body-wrapper ends -->
  </div>

 
 @include('themes.js')

<!-- End custom js for this page-->

</body>

</html>

