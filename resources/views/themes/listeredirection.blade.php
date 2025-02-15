<!DOCTYPE html>
<html lang="fr-TN">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Liste de redirection</title>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  @include('themes.style')
 </head>

<body>
<div class="container-scroller">
    <!-- Navbar and Sidebar -->
    @include('themes.header')
    @include('themes.sideleft')

    <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Listes des Redirections</h5>
                  @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
 
<input type="text" id="searchInput" class="form-control mb-3" placeholder="Rechercher une redirection..." onkeyup="searchTable()">

                  <div class="table-responsive">
                  <table id="revendeursTable" class="table table-responsive" style="width:100%;">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th></th>  <!-- Ajouter la colonne pour l'équipe -->
                            <th>Revendeur</th>
                            <th>Client</th>
                            <th>Téléphone</th>
                            <th>Commander</th>
                            <th>Detail de client</th>                            <th>Status</th>
                            <th>Change Status </th>
                            <th>Change Revendeur </th>
                             <th>Date de Redirection</th>
                            <th>Date de Modification</th>

                          </tr>
                        </thead>
                        <tbody>
    @if($redirections && $redirections->isNotEmpty())
        @foreach($redirections as $redirection)
            <tr>
                <td>#{{ $redirection->ref_redirection }}</td>
               
<td> <img src="{{ asset('storage/'.$redirection->equipe_image) }}" title="{{ $redirection->equipe_nom }}" width="20"/></td>
                <td>{{ $redirection->revendeur_nom }}</td>
                <td>{{ $redirection->client_nom }} {{ $redirection->client_prenom }}</td>
                <td><a href="tel:+216{{ $redirection->client_phone }}">{{ $redirection->client_phone }}</a></td>
                <td>
                    <!-- Toggle Button pour afficher les produits associés -->
                    <button class="btn btn-info btn-sm toggle-button" id="toggle-{{ $redirection->ref_redirection }}" 
                            data-toggle="collapse" 
                            data-target="#collapse-{{ $redirection->ref_redirection }}" 
                            aria-expanded="false" 
                            aria-controls="collapse-{{ $redirection->ref_redirection }}">
                        <i class="fas fa-arrow-down"></i> 
                    </button>
                    <div id="collapse-{{ $redirection->ref_redirection }}" class="collapse">
                        <div clsass="card-body">
                            @if(!empty($redirection->produits_avec_prix))
                                <ul class="list-unstyled">
                                    @foreach(explode(',', $redirection->produits_avec_prix) as $produit)
                                        <li>{{ $produit }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>Aucun produit associé</p>
                            @endif
                        </div>
                    </div>
                </td>

<td> <button class="btn btn-light" data-toggle="modal" data-target="#detailModal{{$redirection->ref_redirection}}">
                            <i class="ti-info"></i>  
                          </button>
</td>             <div class="modal fade" id="detailModal{{  $redirection->ref_redirection }}" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="detailModalLabel">Détails du Client -{{ $redirection->client_nom }} {{ $redirection->client_prenom }}</h5>
                              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                              </button>      
                            </div>
                            <div class="modal-body">
                              <p><strong>Nom Prenom :{{ $redirection->client_nom }} {{ $redirection->client_prenom }}</strong> </p>
                              <p><strong>Email :</strong> <a href="mailto:{{ $redirection->client_email }}">{{ $redirection->client_email }} </a></p>
                              	
 
                              <p><strong>Téléphone:</strong> <a href="tel:{{ $redirection->client_phone }}">{{ $redirection->client_phone }} </a></p>
 
                                <p><strong>Adresse:  </strong> {{ $redirection->client_adresse }}</p>
                                <p><strong>Sexe: </strong>  {{ $redirection->sexe }}</p>

                               <p><strong>Date de Naissance de client:</strong>{{ $redirection->date_naissance_client }} </p>
                               <p><strong>Source de Redirection :</strong> {{ $redirection->source_red }}</p>
                               <p><strong>Revendeur:</strong> {{ $redirection->revendeur_nom }}</p>

                            </div>
                          </div>
                        </div>
                          <!-- Toggle "Change" Button -->
       

                <td> 
                @if($redirection->etat_red == 1)
    <i class="fas fa-paper-plane text-warning" title="Envoyer au Revendeur" style="font-size: 20px;"></i>
@elseif($redirection->etat_red == 0)
    <i class="fas fa-check-circle text-primary" title="Validé" style="font-size: 20px;"></i>
@elseif($redirection->etat_red == 2)
    <i class="fas fas fa-times-circle text-danger" title="Annuel" style="font-size: 20px;"></i>
@elseif($redirection->etat_red == 3)
    <i class="fas fa-redo text-info" title="Renvoyer" style="font-size: 20px;"></i>
@else
    <i class="fas fa-times-circle text-danger" title="Annulé" style="font-size: 20px;"></i>
@endif

                <td>
                    <!-- Bouton pour ouvrir la modale -->
                    <button class="btn btn-warning btn-sm" data-toggle="modal" 
                            data-target="#changeEtatModal-{{ $redirection->ref_redirection }}" 
                            title="Changer l'état">
                        <i class="fas fa-exchange-alt"></i> 
                    </button>
                    
                    <!-- Autres actions -->
        

                </td>
                <td>
            
                
                <button class="btn btn-outline-light btn-fw" data-toggle="modal" 
        data-target="#changeRevendeurModal-{{ $redirection->ref_redirection }}" 
        title="Changer Revendeur">
    <i class="ti-control-shuffle"></i>
</button>
            </td>
            <td>
            <button class="btn btn-primary" data-toggle="modal" data-target="#editRedirectionModal" data-ref="{{ $redirection->ref_redirection }}"
                            data-client-nom="{{ $redirection->client_nom }}" data-client-prenom="{{ $redirection->client_prenom }}" data-etat="{{ $redirection->etat_red }}">
                            Modifier
                        </button>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editRedirectionModal">
    Modifier Redirection
</button>



                        <td>{{ \Carbon\Carbon::parse($redirection->date_premiere_redirection)->locale('fr_FR')->isoFormat('ddd, DD MMM YYYY à HH[h]mm') }}</td>
<td>{{ \Carbon\Carbon::parse($redirection->date_derniere_redirection)->locale('fr_FR')->isoFormat('ddd, DD MMM YYYY à HH[h]mm') }}</td>
        </tr>
  

<!-- Modal for Update Redirection -->
<div class="modal fade" id="editRedirectionModal" tabindex="-1" role="dialog" aria-labelledby="editRedirectionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRedirectionModalLabel">Modifier la Redirection</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('redirections.update', ['reference' => $redirection->ref_redirection]) }}" method="POST" id="updateRedirectionForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="client_nom">Nom Client</label>
                        <input type="text" class="form-control" value="{{ $redirection->client_nom }}" name="client_nom" id="modalClientNom" required>
                    </div>
                    <div class="form-group">
                        <label for="client_prenom">Prénom Client</label>
                        <input type="text" class="form-control" value="{{ $redirection->client_prenom }}" name="client_prenom" id="modalClientPrenom" required>
                    </div>
                    <div class="form-group">
                        <label for="client_email">Email Client</label>
                        <input type="email" class="form-control" name="client_email" value="{{ $redirection->client_email }}" id="modalClientEmail" required>
                    </div>
                    <div class="form-group">
                        <label for="client_phone">Téléphone Client</label>
                        <input type="text" class="form-control" name="client_phone" value="{{ $redirection->client_phone }}" id="modalClientPhone" required>
                    </div>
                    <div class="form-group">
                        <label for="client_adresse">Adresse Client</label>
                        <input type="text" class="form-control" name="client_adresse" value="{{ $redirection->client_adresse }}" id="modalClientAdresse" required>
                    </div>
                    <!-- Ajoutez d'autres champs de formulaire ici si nécessaire -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$('#editRedirectionModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Bouton qui a déclenché la modale
    var refRedirection = button.data('ref');
    var clientNom = button.data('client-nom');
    var clientPrenom = button.data('client-prenom');
    var clientEmail = button.data('email');
    var clientPhone = button.data('phone');
    var clientAdresse = button.data('adresse');
    var etat = button.data('etat');
    
    var modal = $(this);
    modal.find('#modalRefRedirection').val(refRedirection);
    modal.find('#modalClientNom').val(clientNom);
    modal.find('#modalClientPrenom').val(clientPrenom);
    modal.find('#modalClientEmail').val(clientEmail);
    modal.find('#modalClientPhone').val(clientPhone);
    modal.find('#modalClientAdresse').val(clientAdresse);
    modal.find('#modalEtatRed').val(etat);
    
    // Mise à jour de l'URL du formulaire pour inclure la référence de la redirection
    var actionUrl = "{{ route('redirections.update', '') }}/" + refRedirection;
    modal.find('#updateRedirectionForm').attr('action', actionUrl);
});

    </script>
<!-- Modale de mise à jour -->
 
<!-- model pour changer Revendeur -->
 <!-- Modal pour changer le revendeur -->
<div class="modal fade" id="changeRevendeurModal-{{ $redirection->ref_redirection }}" tabindex="-1" role="dialog" aria-labelledby="changeRevendeurModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeRevendeurModalLabel">Changer le revendeur pour cette redirection</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Formulaire de changement de revendeur -->
            <form action="{{ route('change.revendeur') }}" method="POST">
                @csrf
                @method('PATCH')

                <!-- Ajouter la référence de la redirection dans un champ caché -->
                <input type="hidden" name="reference" value="{{ $redirection->ref_redirection }}">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="revendeur">Sélectionnez un nouveau revendeur :</label>
                        <!-- Liste déroulante pour sélectionner un nouveau revendeur -->
                        <select name="new_revendeur_id" id="revendeur" class="form-control">
                            @foreach ($revendeurs as $revendeur)
                                <option value="{{ $revendeur->id }}">{{ $revendeur->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bouton pour afficher le modal -->


<!-- -->
            <!-- Modale pour changer l'état -->
            <div class="modal fade" id="changeEtatModal-{{ $redirection->ref_redirection }}" tabindex="-1" role="dialog" aria-labelledby="changeEtatModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="changeRevendeurModallabel">Changer l'état de la redirection</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>                       

                        <form action="{{ route('redirections.updateEtat', $redirection->ref_redirection) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="etat">Sélectionnez le nouvel état :</label>
                                    <select name="etat_red" id="etat" class="form-control">
                                        <option value="0" {{ $redirection->etat_red == 0 ? 'selected' : '' }}>Validé</option>
                                        <option value="2" {{ $redirection->etat_red == 2 ? 'selected' : '' }}>Annulé</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary">Valider</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <tr>
            <td colspan="9" class="text-center">Aucune redirection trouvée.</td>
        </tr>
    @endif
</tbody>

                      </table>
                    </div>
    


<div class="d-flex justify-content-center mt-4">
                    {{ $redirections->links('pagination::bootstrap-5') }}
                  </div>
                  </div>
               </div>
            </div>
          </div>
        </div>
    </div>   
</div>

<!-- JS and Plugins -->
 
@include('themes.js')
 
<script>
$(document).ready(function() {
     
    // Gestion du toggle de l'icône pour activer/désactiver l'affichage
    $('.toggle-button').on('click', function() {
        var targetId = $(this).data('target');
        var icon = $(this).find('i');

        if ($(targetId).hasClass('collapse')) {
            $(targetId).collapse('show');
            icon.removeClass('fa-arrow-down').addClass('fa-arrow-up');
            $(this).prop('disabled', true); // Désactiver le bouton après le clic
        } else {
            $(document).ready(function() {
    $('.collapse').collapse('hide'); // Ensure collapse is hidden initially
});
        }
    });
});
</script>

<!-- JS pour gestion de la modale -->
 
     
 
//pagination 

 
 
    <script>
     function searchTable() {
        var input, filter, table, tr, td, i, j, txtValue;
        input = document.getElementById('searchInput');
        filter = input.value.toUpperCase(); // Rendre la recherche insensible à la casse
        table = document.getElementById("revendeursTable");
        tr = table.getElementsByTagName("tr"); // Récupérer toutes les lignes du tableau

        // Boucle sur chaque ligne du tableau (en excluant l'en-tête)
        for (i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td");
            if (td) {
                txtValue = "";
                // Boucle sur toutes les cellules de la ligne
                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue += td[j].textContent || td[j].innerText; // Concaténer tous les textes des colonnes
                    }
                }
                // Vérifier si le texte de la ligne contient la valeur de recherche
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = ""; // Afficher la ligne
                } else {
                    tr[i].style.display = "none"; // Cacher la ligne
                }
            }       
        }
    }
</script>

 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
