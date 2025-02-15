<?php

 
namespace App\Http\Controllers;

use App\Models\Redirection;
use Carbon\Carbon; // Utilisation correcte de la classe Carbon
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Revendeur;
use App\Models\Produit;
use App\Models\Direction;
use App\Models\Commercial;
use App\Models\Evenement;
use Illuminate\Support\Facades\Mail;
use App\Mail\RedirectionCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB as FacadeDB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller {
     // Affiche le formulaire pour créer une nouvelle redirection
     public function create()
     {
        // Récupérer tous les revendeurs
        $revendeurs = Revendeur::all();
        
        // Passer les revendeurs à la vue 'themes.ajouterredirection'


        $produit = produit::all();


        
        // Passer les revendeurs à la vue 'themes.ajouterredirection'
        return view('themes.sites.orders', compact('revendeurs', 'produit',));
    }
    
}