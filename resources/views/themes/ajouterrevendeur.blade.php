<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Ajouter Revendeur</title>
  @include('themes.style')
</head>

<body>
  <div class="container-scroller">
    @include('themes.header')
    @include('themes.sideleft')

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
                   <h4 class="cart-title">Ajouter Revendeur</h4>

                  <!-- Alertes -->
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

                  <!-- Formulaire d'ajout de revendeur -->
                  <form id="revendeurForm" action="{{ route('revendeurs.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                      <!-- Nom du Revendeur -->
                      <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Revendeur:</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Entrez le nom du revendeur" required />
                      </div>

                      <!-- Nom du Responsable eCommerce -->
                      <div class="col-md-6 mb-3">
                        <label for="nometprenom" class="form-label">Responsable E-commerce:</label>
                        <input type="text" id="nometprenom" name="nometprenom" class="form-control" placeholder="Entrez le nom du responsable" required />
                      </div>
                    </div>

                    <div class="row">
                      <!-- Emails du Responsable eCommerce -->
                      <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email du Responsable eCommerce 1:</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Entrez l'email du responsable" required />
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="email_2" class="form-label">Email du Responsable eCommerce 2:</label>
                        <input type="email" id="email_2" name="email_2" class="form-control" placeholder="Entrez l'email du responsable" />
                      </div>
                    </div>

                    <div class="row">
                      <!-- Téléphones du Responsable eCommerce -->
                      <div class="col-md-6 mb-3">
                        <label for="telephone" class="form-label">Téléphone du Responsable eCommerce 1:</label>
                        <input type="tel" id="telephone" name="telephone" class="form-control" placeholder="Entrez le numéro de téléphone" required pattern="\d{8}" title="Le numéro de téléphone doit contenir exactement 8 chiffres" />
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="telephone_2" class="form-label">Téléphone eCommerce 2:</label>
                        <input type="tel" id="telephone_2" name="telephone_2" class="form-control" placeholder="Entrez le numéro de téléphone" pattern="\d{8}" title="Le numéro de téléphone doit contenir exactement 8 chiffres" />
                      </div>
                    </div>

                    <div class="row">
                      <!-- Logo du Revendeur -->
                      <div class="col-md-6 mb-3">
                        <label for="logo" class="form-label">Logo du Revendeur:</label>
                        <input type="file" id="logo" name="logo" class="form-control" accept="image/*" />
                        <div id="logoPreview" class="mt-2" style="display: none;">
                          <img id="previewLogo" alt="Logo Preview" class="img-thumbnail" style="max-height: 100px;" />
                        </div>
                      </div>

                      <!-- Choix de la Direction -->
                      <div class="col-md-6 mb-3">
                        <label for="direction_id" class="form-label">Choisissez la Direction:</label>
                        <select name="direction_id" id="direction_id" class="form-control" required>
                          <option value="">Choisir une Direction</option>
                          @foreach ($directions as $direction)
                            <option value="{{ $direction->id }}">{{ $direction->nom }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="row">
                      <!-- Remarque du Revendeur -->
                      <div class="col-md-6 mb-3">
                        <label for="remarque" class="form-label">Remarque du Revendeur:</label>
                        <textarea id="remarque" name="remarque" class="form-control" placeholder="Ajoutez des remarques ici..."></textarea>
                      </div>

                      <div class="col-md-6 mb-3">
                        <label for="revendeur_type" class="form-label">Catégorie du Revendeur:</label>
                        <select name="revendeur_type" id="revendeur_type" class="form-control" multiple required>
                          <option value="Ecommerce">E-commerce</option>
                          <option value="parapharmacie">Parapharmacie</option>
                          <option value="GMS">Grand Surface</option>
                          <option value="boutiqueSpecialiser">Boutique Spécialisée</option>
                        </select>
                      </div>
                    </div>

                    <div class="row">
                      <!-- Adresse du Revendeur -->
                      <div class="col-md-6 mb-3">
                        <label for="address_red" class="form-label">Adresse du Revendeur (Lien Google Maps):</label>
                        <textarea id="address_red" name="address_red" class="form-control" placeholder="Ajoutez l'adresse ici..."></textarea>
                      </div>

                      <div class="col-md-6 mb-3">
                        <label for="commercial_id" class="form-label">Choisir un Commercial:</label>
                        <select name="commercial_id" id="commercial_id" class="form-control" required>
                          <option value="">Choisir un commercial</option>
                          @foreach ($commercials as $commercial)
                            <option value="{{ $commercial->id }}">{{ $commercial->nomprenom }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="mb-3">

<input type="hidden" name="id_team" value="{{ $team->id }}">
</div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">Ajouter Revendeur</button>
                    </div>
                  </form>
               </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  @include('themes.js')

  <script>
    // Script pour gérer l'aperçu du logo
    const logoInput = document.getElementById('logo');
    const logoPreview = document.getElementById('logoPreview');
    const previewLogo = document.getElementById('previewLogo');

    logoInput.addEventListener('change', function () {
      const file = logoInput.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          previewLogo.src = e.target.result;
          logoPreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
      }
    });
  </script>
</body>

</html>
