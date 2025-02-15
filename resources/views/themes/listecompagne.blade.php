<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Liste des Campagnes</title>
  @include('themes.style')
  <style>
    /* Assurez-vous que la table soit responsive */
    .table-responsive {
      overflow-x: auto;
    }
  </style>
</head>

<body>
  <div class="container-scroller">
    @include('themes.header')
    @include('themes.sideleft')

    {{-- Message de succès --}}
    @if (session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
          <div class="col-md-12 grid-margin">
            <div class="row">
              <div class="col-12 col-md-8 mb-4 mb-md-0"></div>
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

        <!-- Liste des campagnes -->
        <div class="row">
          <div class="col-md-12 grid-margin">
            <div class="card">
              <div class="card-body">
                <h1 class="text-center mb-4">Liste des Campagnes</h1>

                <!-- Bouton pour ajouter une campagne -->
                <a href="{{ route('themes.ajoutercompagne') }}" class="btn btn-success mb-3">Ajouter une campagne</a>

                <!-- Itérer sur les événements regroupés -->
                <div class="table-responsive">
                  @foreach ($groupedCampaigns as $eventName => $campaignGroup)
                    <div class="col-12">
                      <h3>{{ $eventName }}</h3>
                      <table class="table table-hover">
                        <thead>
                          <tr>
                            <th>Nom de la Campagne</th>
                            <th>Type</th>
                            <th>Budget</th>
                            <th>Date de Début</th>
                            <th>Date de Fin</th>
                            <th>Influenceur</th>
                            <th>Plateforme</th>
                            <th>Montant</th>
                            <th>Nombre de Reels</th>
                            <th>UGC</th>
                            <th>Montant UGC</th>
                            <th>Plateforme UGC</th>
                            <th>Marque</th> <!-- Nouvelle colonne pour afficher la marque -->
                            <th>total_sum</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($campaignGroup as $campaign)
                            <tr>
                            <td>{{ $campaign->marque_name }}</td> <!-- Affichage du nom de la marque -->
                              <td>{{ ucfirst($campaign->campaign_type) }}</td>
                              <td>
                                @if($campaign->campaign_budget)
                                  {{ $campaign->campaign_budget }} TND
                                @else
                                  &mdash; <!-- Si vide, afficher un tiret -->
                                @endif
                              </td>
                              <td>{{ $campaign->start_date }}</td>
                              <td>{{ $campaign->end_date }}</td>
                              <td>{{ $campaign->nom_influencer }}</td>
                              <td>{{ $campaign->plateforme }}</td>
                              <td>
                                @if($campaign->montant)
                                  {{ $campaign->montant }} TND
                                @else
                                  &mdash; <!-- Si vide, afficher un tiret -->
                                @endif
                              </td>
                              <td>{{ $campaign->nombre_reels }}</td>
                              <td>{{ $campaign->nom_ugc }}</td>
                              <td>
                                @if($campaign->montant_ugc)
                                  {{ $campaign->montant_ugc }} TND
                                @else
                                  &mdash; <!-- Si vide, afficher un tiret -->
                                @endif
                              </td>
                              <td>{{ $campaign->plateforme_ugc }}</td>
                             
                              <td>{{ $campaign->total_sum }}</td> <!-- Affichage du nom de la marque -->

                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      @include('themes.js')
    </div>
  </div>
</body>

</html>
