<?php

namespace App\Http\Controllers;

use App\Models\Redirection;
use App\Models\Brand;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EvenementsController extends Controller
{
    // Afficher la liste des événements
    public function create()
    {
        // Récupération de tous les événements
        $evenements = Evenement::all();
    
        // Récupération de toutes les marques (brands)
        $brands = Brand::all();
    
        // Retourne la vue avec les données compactées
        return view('themes.ajouterevenement', compact('evenements', 'brands'));
    }
    
    // Afficher le tableau de bord des événements avec chiffre d'affaires
    public function index()
    {
        // Récupérer les événements paginés avec leurs marques associées via la relation 'brand'
        $evenements = Evenement::with('brand')->paginate(10);  // 10 événements par page
    
        // Retourner la vue 'themes.listeevenements' avec les événements paginés
        return view('themes.listeevenement', compact('evenements'));
    }
    

    public function showDashboard()
    {
        // Récupérer tous les événements avec leurs redirections associées
        $evenements = Evenement::with(['redirections' => function ($query) {
            $query->select('id', 'evenements_id', 'prix_produit', 'reduction_produit');
        }])->get();

        // Calculer le chiffre d'affaires pour chaque événement
        $chiffreAffaireParEvenement = $evenements->map(function ($evenement) {
            $chiffreAffaire = $evenement->redirections->sum(function ($redirection) {
                // Calcul du chiffre d'affaires en tenant compte de la réduction
                return $redirection->prix_produit * (1 - $redirection->reduction_produit / 100);
            });

            return [
                'evenement_id' => $evenement->id,
                'evenement_nom' => $evenement->nom,
                'chiffre_affaire' => $chiffreAffaire
            ];
        });

        // Retourner la vue avec les données calculées
        return view('themes.dashboard_evenement', compact('chiffreAffaireParEvenement'));
    }

    // Ajouter un événement
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'brand_id' => 'required|exists:marques,id',
            'id_team'=>'required|exists:team,id',
        ]);

        Evenement::create($request->all());

        return redirect()->route('themes.ajouterevenement')->with('success', 'Événement créé avec succès.');
    }

    // Afficher la vue pour créer une compagne
    public function createCompagne(): View
    {
        $evenements = Evenement::all();
        $brands = Brand::all();  // Récupérer toutes les marques (brands)
        return view('themes.ajoutercompagne', compact('evenements', 'brands'));
    }

    // Mettre à jour un événement
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'brand_id' => 'required|exists:marques,id',
        ]);

        $evenement = Evenement::findOrFail($id);
        $evenement->update($request->all());

        return redirect()->route('themes.listeevenement')->with('success', 'Événement mis à jour avec succès.');
    }

    // Supprimer un événement
    public function destroy(Evenement $evenement)
    {
        $evenement->delete();
        return redirect()->route('themes.listeevenement')->with('success', 'Événement supprimé avec succès.');
    }

    // Éditer un événement (retour JSON)
    public function edit($id)
    {
        $evenement = Evenement::findOrFail($id);
        return response()->json($evenement);
    }
}
