<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Brand;
use App\Models\Revendeur;
use Illuminate\Http\Request;
use Automattic\WooCommerce\Client;
use Illuminate\Support\Facades\Http;

class SiteController extends Controller
{
 
    public function create()
   {
       // Récupérer toutes les marques
       $brands = Brand::all();
       $revendeurs = Revendeur::all(); 
       $produits = Produit::all();
  
   
       // Retourner la vue avec la variable 'brands'
       return view('sites.create', compact('brands','revendeurs','produits'));  // Assurez-vous que vous passez 'brands' à la vue 'sites.create'
   }
   
    
    // Ajouter un site dans la base de données
    public function store(Request $request)
    {
        // Validation des données du formulaire
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'consumer_key' => 'required|string',
            'consumer_secret' => 'required|string',
            'brand_id' => 'required|exists:marques,id',  // Validation de l'existence de la marque
        ]);
    
        // Création d'un nouveau site
        $site = new Site();
        $site->name = $request->name;
        $site->url = $request->url;
        $site->consumer_key = $request->consumer_key;
        $site->consumer_secret = $request->consumer_secret;
        $site->brand_id = $request->brand_id;  // Enregistrement de la marque
        $site->etat_s = 1;  // Définir 'etat_s' par défaut à 1
        $site->save();
    
        return redirect()->route('sites.index')->with('success', 'Site ajouté avec succès.');
    }
 // Récupérer toutes les marques

 // Retourner la vue avec les données des équipes et des marques
     // Afficher la liste des sites
    public function index()
    {
        $brands = Brand::all();

        $sites = Site::all();
        return view('themes.sites.index', compact('sites', 'brands'));

    }
   // Afficher le formulaire pour ajouter un site
  
   
    // Afficher les commandes d'un site particulier
  // Afficher les commandes d'un site particulier
// Afficher toutes les commandes de tous les sites
public function fetchOrders()
{
    $sites = Site::all(); // Récupère tous les sites
    $ordersData = [];

    foreach ($sites as $site) {
        // Initialiser le client WooCommerce pour chaque site
        $client = new Client(
            $site->url, // L'URL du site
            $site->consumer_key, // Consumer key
            $site->consumer_secret, // Consumer secret
            [
                'version' => 'wc/v3', // Version de l'API WooCommerce
            ]
        );

        // Appel API pour récupérer les commandes du site
        try {
            $orders = $client->get('orders'); // Récupère toutes les commandes
            $ordersData[$site->name] = $orders; // Stocke les commandes par nom de site
        } catch (\Exception $e) {
            $ordersData[$site->name] = 'Erreur : ' . $e->getMessage(); // En cas d'erreur, affiche un message
        }
    }

    // Retourne la vue avec les commandes de tous les sites
    return view('themes.sites.order', compact('ordersData'));
}
public function show($id)
{
    $site = Site::findOrFail($id); // Récupérer le site par ID
    $orders = []; // Initialisation des commandes
    $revendeurs = Revendeur::all(); // Récupérer tous les revendeurs

    try {
        // Construire l'URL de l'API REST
        $apiUrl = $site->url;

        // Effectuer une requête HTTP avec les paramètres d'authentification
        $response = Http::withBasicAuth($site->consumer_key, $site->consumer_secret)
            ->get($apiUrl);

        if ($response->successful()) {
            // Décoder les commandes si la requête est réussie
            $orders = $response->json();
        } else {
            // Gérer les erreurs HTTP
            $orders = 'Erreur : ' . $response->status() . ' - ' . $response->body();
        }
    } catch (\Exception $e) {
        // Gérer les exceptions
        $orders = 'Erreur : ' . $e->getMessage();
    }

    // Retourner les commandes et les revendeurs à la vue
    return view('themes.sites.order', compact('orders', 'site', 'revendeurs'));
}
  // Ajouter une redirection
  public function storeRedirection(Request $request)
  {
      // Validation des données de la redirection
      $request->validate([
          'brand_id' => 'required|integer',
          'reference' => 'nullable|string',
          'product_id' => 'required|array|min:1|max:100',
          'nom_produit' => 'required|array|min:1|max:100',
          'prix_produit' => 'required|array|min:1|max:100',
          'reduction_produit' => 'required|array|min:1|max:100',
          'client_nom' => 'required|string|max:255',
          'client_prenom' => 'required|string|max:255',
          'client_email' => 'required|email|max:255',
          'client_phone' => 'required|string|max:20',
          'client_adresse' => 'required|string',
          'revendeur_id' => 'required|string|max:255',
          'date_naissance_client' => 'required|date',
          'sexe' => 'required|string|max:255',
          'etat_red' => 'nullable|integer',
          'source_red' => 'required|string',
          'evenements_id' => 'required|string|max:255',
      ]);

      // Récupérer le site de redirection et les commandes
      $site = Site::findOrFail($request->brand_id); // Trouver le site par l'ID de la marque
      $orders = [];
      $revendeurs = Revendeur::all();

      try {
          $apiUrl = $site->url . '/orders';
          $response = Http::withBasicAuth($site->consumer_key, $site->consumer_secret)->get($apiUrl);

          if ($response->successful()) {
              $orders = $response->json();
          } else {
              $orders = 'Erreur : ' . $response->status() . ' - ' . $response->body();
          }
      } catch (\Exception $e) {
          $orders = 'Erreur : ' . $e->getMessage();
      }

      // Enregistrer la redirection dans la base de données
      $redirection = new Redirection();
      $redirection->brand_id = $request->brand_id;
      $redirection->reference = $request->reference;
      $redirection->product_id = json_encode($request->product_id); // Convertir en JSON
      $redirection->nom_produit = json_encode($request->nom_produit); // Convertir en JSON
      $redirection->prix_produit = json_encode($request->prix_produit); // Convertir en JSON
      $redirection->reduction_produit = json_encode($request->reduction_produit); // Convertir en JSON
      $redirection->client_nom = $request->client_nom;
      $redirection->client_prenom = $request->client_prenom;
      $redirection->client_email = $request->client_email;
      $redirection->client_phone = $request->client_phone;
      $redirection->client_adresse = $request->client_adresse;
      $redirection->revendeur_id = $request->revendeur_id;
      $redirection->date_naissance_client = $request->date_naissance_client;
      $redirection->sexe = $request->sexe;
      $redirection->etat_red = $request->etat_red;
      $redirection->source_red = $request->source_red;
      $redirection->evenements_id = $request->evenements_id;
      $redirection->save();

      // Retourner la vue avec un message de succès et les informations nécessaires
      return redirect()->route('themes.sites.orders')
                       ->with('success', 'Redirection créée avec succès.')
                       ->with('orders', $orders)
                       ->with('revendeurs', $revendeurs);
  }


}
