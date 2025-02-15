<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Liste et Gestion des Sites</title>
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
            <h1 class="text-center mb-4 display-4">Liste et Gestion des Sites</h1>
          </div>
        </div>

        <!-- Section Formulaire -->
        <div class="row">
          <div class="col-md-12 stretch-card grid-margin">
            <div class="card">
              <div class="card-body">
                <h2 class="card-title">Liste des Sites</h2>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSiteModal">
                  Ajouter un nouveau site
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Liste des Sites -->
        <div class="col-md-12 stretch-card grid-margin">
            <div class="card">
              <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <table class="table table-hover mt-4">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nom</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($sites as $site)
                  <tr>
                    <td>{{ $site->id }}</td>
                    <td>{{ $site->name }}</td>
                    <td>
                      <a href="{{ route('sites.show', $site->id) }}" class="btn btn-info btn-sm">Voir commandes</a>
                      <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSiteModal" data-site="{{ json_encode($site) }}">
                        Modifier
                      </button>

                      <form action="{{ route('sites.updateStatus', $site->id) }}" method="POST" class="d-inline">
                        @csrf
                        <div class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" id="statusToggle{{ $site->id }}" name="etat_s" value="1" {{ $site->etat_s == 1 ? 'checked' : '' }} onchange="this.form.submit()">
                          <label class="form-check-label" for="statusToggle{{ $site->id }}">
                            {{ $site->etat_s == 1 ? 'Actif' : 'Inactif' }}
                          </label>
                        </div>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="text-center">Aucun site disponible.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
        </div>
      </div>
      <!-- Modal pour Ajouter un site -->
      <div class="modal fade" id="addSiteModal" tabindex="-1" aria-labelledby="addSiteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addSiteModalLabel">Ajouter un nouveau site</h5>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>            </div>
            <div class="modal-body">
              <form action="{{ route('sites.store') }}" method="POST" class="row g-3">
                @csrf
                <!-- Formulaire -->
                <div class="col-md-4">
                  <label for="brand" class="form-label">Marque :</label>
                  <select name="brand_id" id="brand" class="form-control" required>
                    <option value="">Sélectionnez une marque</option>
                    @foreach ($brands as $brand)
                      <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-4">
                  <label for="name" class="form-label">Nom du site :</label>
                  <input type="text" id="name" name="name" class="form-control" placeholder="Nom du site" required>
                </div>
                <div class="col-md-4">
                  <label for="url" class="form-label">URL :</label>
                  <input type="url" id="url" name="url" class="form-control" placeholder="https://exemple.com" required>
                </div>
                <div class="col-md-12">
                  <label for="consumer_key" class="form-label">Consumer Key :</label>
                  <input type="text" id="consumer_key" name="consumer_key" class="form-control" required>
                </div>
                <div class="col-md-12">
                  <label for="consumer_secret" class="form-label">Consumer Secret :</label>
                  <input type="text" id="consumer_secret" name="consumer_secret" class="form-control" required>
                </div>
                <div class="col-12">
                  <button type="submit" class="btn btn-primary w-100">Ajouter le site</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal pour Modifier un site -->
      <div class="modal fade" id="editSiteModal" tabindex="-1" aria-labelledby="editSiteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editSiteModalLabel">Modifier le site</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form action="" method="POST" class="row g-3" id="editSiteForm">
                @csrf
                @method('PUT')
                <!-- Formulaire -->
                <div class="col-md-4">
                  <label for="editBrand" class="form-label">Marque :</label>
                  <select name="brand_id" id="editBrand" class="form-control" required>
                    <option value="">Sélectionnez une marque</option>
                    @foreach ($brands as $brand)
                      <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-4">
                  <label for="editName" class="form-label">Nom du site :</label>
                  <input type="text" id="editName" name="name" class="form-control" required>
                </div>
                <div class="col-md-4">
                  <label for="editUrl" class="form-label">URL :</label>
                  <input type="url" id="editUrl" name="url" class="form-control" required>
                </div>
                <div class="col-md-12">
                  <label for="editConsumerKey" class="form-label">Consumer Key :</label>
                  <input type="text" id="editConsumerKey" name="consumer_key" class="form-control" required>
                </div>
                <div class="col-md-12">
                  <label for="editConsumerSecret" class="form-label">Consumer Secret :</label>
                  <input type="text" id="editConsumerSecret" name="consumer_secret" class="form-control" required>
                </div>
                <div class="col-12">
                  <button type="submit" class="btn btn-warning w-100">Mettre à jour le site</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    @include('themes.js')

    <!-- JavaScript pour remplir le modal -->
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('[data-bs-target="#editSiteModal"]');

        editButtons.forEach(button => {
          button.addEventListener('click', function () {
            const site = JSON.parse(this.getAttribute('data-site'));

            // Remplir les champs du modal avec les données du site
            document.getElementById('editSiteForm').action = `/sites/${site.id}`;
            document.getElementById('editBrand').value = site.brand_id;
            document.getElementById('editName').value = site.name;
            document.getElementById('editUrl').value = site.url;
            document.getElementById('editConsumerKey').value = site.consumer_key;
            document.getElementById('editConsumerSecret').value = site.consumer_secret;
          });
        });
      });
    </script>
  </div>
</body>

</html>
