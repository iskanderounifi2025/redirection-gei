@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Les Redirections annulees</h2>

   
</div>
@endsection


<!DOCTYPE html>
<html lang="fr">
<head>
  <!-- Include Themify icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/themify-icons/0.2.0/css/themify-icons.min.css">

  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Liste des Produits</title>

  <!-- plugins:css -->
  @include('themes.style')
</head>
<body>
  <div class="container-scroller">
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
                      <i class="ti-calendar"></i> Aujourd'hui ({{ now()->format('d M Y') }} - {{ now()->format('H:i') }})
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <p class="card-title">Les Redirections Valider</p>
                <div class="table-responsive">
                <table class="table table-respensive">
        <thead>
            <tr>
                <th>Référence</th>
                <th>Produits</th>
                <th>Client</th>
                <th>Revendeur</th>
                <th>Total</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($redirections as $redirection)
                <tr>
                    <td>{{ $redirection->ref_redirection }}</td>
                    <td>{{ $redirection->produits_avec_prix }}</td>
                    <td>{{ $redirection->client_nom }} {{ $redirection->client_prenom }}</td>
                    <td>{{ $redirection->revendeur_nom }}</td>
                    <td>{{ number_format($redirection->total_commande, 2) }} TND</td>
                    <td>{{ $redirection->date_derniere_redirection }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>

  @include('themes.js')
 
 
</body>
</html>
