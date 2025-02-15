<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Ajouter Campagne</title>
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
                  <h1 class="text-center mb-4 display-4 display-md-3 display-lg-2">Ajouter Campagne</h1>

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

                  <form id="campaignForm" action="{{ route('campaign.store') }}" method="POST" class="row g-3 mb-4" enctype="multipart/form-data">
                    @csrf
                   
                    <div class="col-md-4 col-12">
                      <label for="nom" class="form-label">Nom de la Campagne:</label>
                      <input type="text" id="nom" name="nom" class="form-control" placeholder="Nom de la Campagne" required />
                    </div>
                    <div class="col-md-4 col-12">
                      <label for="evenement" class="form-label">Nom de l'Événement:</label>
                      <select name="evenement_id" class="form-control">
    <option value="">Sélectionnez un événement</option>
    @foreach($evenements as $evenement)
        <option value="{{ $evenement->id }}">{{ $evenement->nom }}</option>
    @endforeach
</select>

                    </div>
                    <div class="col-md-4 col-12">
                      <label for="type" class="form-label">Type de Campagne:</label>
                      <select id="type" name="type" class="form-control" required>
                        <option value="" disabled selected>Sélectionnez le Type de Campagne</option>
                        <option value="ads">Publicité (Ads)</option>
                        <option value="influence">Influence</option>
                        <option value="ugc">UGC</option>
                        <option value="urbain">Urbain</option>
                        <option value="tv">TV</option>
                      </select>
                    </div>

                    <!-- Section Ads -->
                    <div id="adsFields" class="d-none col-12">
                      <div class="col-md-12">
                        <label for="budget" class="form-label">Budget:</label>
                        <input type="number" id="budget" name="budget" class="form-control" placeholder="Budget" />
                      </div>

                      <div class="col-md-12">
                        <label for="date_debut" class="form-label">Date de Début:</label>
                        <input type="date" id="date_debut" name="date_debut" class="form-control" />
                      </div>

                      <div class="col-md-12">
                        <label for="date_fin" class="form-label">Date de Fin:</label>
                        <input type="date" id="date_fin" name="date_fin" class="form-control" />
                      </div>
                    </div>

                    <!-- Section Influence -->
                    <div id="influenceFields" class="d-none col-12">
                      <div class="col-md-12">
                        <label for="nom_influencer" class="form-label">Nom de l'Influenceur:</label>
                        <input type="text" id="nom_influencer" name="nom_influencer" class="form-control" placeholder="Nom de l'Influenceur" />
                      </div>

                      <div class="col-md-12">
                        <label for="plateforme" class="form-label">Plateforme:</label>
                        <select id="plateforme" name="plateforme" class="form-control">
                          <option value="" disabled selected>Choisissez une Plateforme</option>
                          <option value="facebook">Facebook</option>
                          <option value="instagram">Instagram</option>
                          <option value="tiktok">TikTok</option>
                        </select>
                      </div>

                      <div class="col-md-12">
                        <label for="montant" class="form-label">Montant:</label>
                        <input type="number" id="montant" name="montant" class="form-control" placeholder="Montant" />
                      </div>

                      <div class="col-md-12">
                        <label for="nombre_reels" class="form-label">Nombre de Reels:</label>
                        <input type="number" id="nombre_reels" name="nombre_reels" class="form-control" placeholder="Nombre de Reels" />
                      </div>
                    </div>

                    <!-- Section UGC -->
                    <div id="ugcFields" class="d-none col-12">
                      <div class="col-md-12">
                        <label for="nom_ugc" class="form-label">Nom de l'UGC:</label>
                        <input type="text" id="nom_ugc" name="nom_ugc" class="form-control" placeholder="Nom de l'UGC" />
                      </div>

                      <div class="col-md-12">
                        <label for="montant_ugc" class="form-label">Montant:</label>
                        <input type="number" id="montant_ugc" name="montant_ugc" class="form-control" placeholder="Montant" />
                      </div>

                      <div class="col-md-12">
                        <label for="plateforme_ugc" class="form-label">Plateforme:</label>
                        <select id="plateforme_ugc" name="plateforme_ugc" class="form-control">
                          <option value="" disabled selected>Choisissez une Plateforme</option>
                          <option value="facebook">Facebook</option>
                          <option value="instagram">Instagram</option>
                          <option value="tiktok">TikTok</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-mb-12">
<input type="hidden" name="id_team" value="{{ $team->id }}">
</div>
                    <div class="col-md-12">
                      <br>
                      <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                    </div>
                  </form>
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
<script>
document.getElementById('type').addEventListener('change', function () {
    const selectedType = this.value;

    // Masquer toutes les sections
    document.getElementById('adsFields').classList.add('d-none');
    document.getElementById('influenceFields').classList.add('d-none');
    document.getElementById('ugcFields').classList.add('d-none');

    // Afficher la section correspondante
    if (selectedType === 'ads') {
        document.getElementById('adsFields').classList.remove('d-none');
    } else if (selectedType === 'influence') {
        document.getElementById('influenceFields').classList.remove('d-none');
    } else if (selectedType === 'ugc') {
        document.getElementById('ugcFields').classList.remove('d-none');
    }
});

  </script>
</html>
