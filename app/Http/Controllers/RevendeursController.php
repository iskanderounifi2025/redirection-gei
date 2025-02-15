<?php

namespace App\Http\Controllers;

use App\Models\Revendeur;
use App\Models\Commercial;
use App\Models\direction;
use App\Models\Redirection;
use Carbon\Carbon; // Utilisation correcte de la classe Carbon
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


use Illuminate\View\View; // Importation correcte pour View

class RevendeursController extends Controller
{
    // Méthode pour afficher le formulaire de création d'un revendeur
    public function create()
    {
        $commercials = Commercial::all();
        $directions = Direction::all();
        
        return view('themes.ajouterrevendeur', compact('commercials', 'directions'));
    }

// Méthode pour afficher la liste des revendeurs
public function index(): View
{
    // Charger les revendeurs avec leurs directions et commerciaux associés
    $revendeurs = Revendeur::with('direction', 'commercials')->get();  // Inclure la relation `commercials`
    
    // Récupérer la liste des commerciaux
    $commercials = Commercial::all(); // Assurez-vous d'utiliser le bon modèle pour les commerciaux
    
    // Retourner la vue avec les données des revendeurs et commerciaux
    return view('themes.listerevendeur', compact('revendeurs', 'commercials'));
}

    
    

    public function indexOfTeam(): View
    {
        $revendeurs = Revendeur::all();
        return view('themes.ajouterequipe', compact('revendeurs'));
    }

    public function getRevendeurs(): View
    {
        $revendeurs = Revendeur::all(); // Récupération des revendeurs
        return view('themes.ajouterredirection', compact('revendeurs')); // Retourne la vue
    }

    public function getRevendeursOrder(): View
    {
        $revendeurs = Revendeur::all(); // Récupération des revendeurs
        return view('themes.sites.orders', compact('revendeurs')); // Retourne la vue
    }
    
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:revendeurs,email',
            'email_2' => 'nullable|email|max:255',
            'nometprenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:15',
            'telephone_2' => 'nullable|string|max:15',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'etat' => 'nullable|integer',
            'remarque' => 'nullable|string|max:255|unique:revendeurs,remarque',
            'commercial_id' => 'nullable|integer',
            'address_red' => 'required|string|max:255',
            'direction_id' => 'nullable|integer',
            'revendeur_type' => 'required|string|max:255',
'id_team'=>'nullable|integer',
        ]);

        // Traitement de l'image
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('revendeur', 'public');
        }

        // Création du revendeur dans la base de données
        Revendeur::create([
            'name' => $request->name,
            'email' => $request->email,
            'email_2' => $request->email_2 ?? '', // Chaîne vide si email_2 est null
            'nometprenom' => $request->nometprenom,
            'telephone' => $request->telephone,
            'telephone_2' => $request->telephone_2 ?? '', // Chaîne vide si telephone_2 est null
            'logo' => $logoPath,
            'etat' => $request->input('etat', 1),
            'remarque' => $request->remarque,
            'direction_id' => $request->direction_id,
            'commercial_id' => $request->commercial_id,
            'address_red' => $request->address_red,
            'revendeur_type' => $request->revendeur_type,
            'id_team'=>$request->id_team,

        ]);

        return redirect()->back()->with('success', 'Revendeur ajouté avec succès !');
    }

    // Méthode pour afficher le formulaire d'édition d'un revendeur
  
    public function indexForAddRedi(): View
    {
        $revendeurs = Revendeur::all(); // Récupérer tous les revendeurs
        return view('themes.ajouterredirection', compact('revendeurs')); // Passer les revendeurs à la vue ajouterredirection
    }

    
    // Méthode pour supprimer un revendeur
    public function destroy($id)
    {
        $revendeur = Revendeur::findOrFail($id);

        // Supprimer le logo si nécessaire
        if ($revendeur->logo) {
            \Storage::disk('public')->delete($revendeur->logo);
        }

        // Suppression du revendeur
        $revendeur->delete();

        // Redirection avec un message de succès
        return redirect()->route('theme.listerevendeur')->with('success', 'Revendeur supprimé avec succès !');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'email_2' => 'nullable|email|max:255',
            'nometprenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'telephone_2' => 'nullable|string|max:20',
            'address_red' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $revendeur = Revendeur::findOrFail($id);
        $revendeur->update($request->all());

        return redirect()->back()->with('success', 'Le revendeur a été mis à jour avec succès.');
    }

    // Affiche les détails d'un revendeur
    public function show($id)
    {
        $revendeur = Revendeur::with('commercials')->findOrFail($id);
        return view('themes.listerevendeur', compact('revendeur'));
    }
    

    
     
    public function dashboard()
    {
        // Récupération de l'année actuelle et de l'année précédente
        $currentYear = date('Y');
        $lastYear = $currentYear - 1;
    
        // Récupération des données des revendeurs avec le calcul du chiffre d'affaire pour l'année en cours et l'année précédente
        $revendeursData = DB::table('revendeurs')
            ->leftJoin('redirections AS r', 'revendeurs.id', '=', 'r.revendeur_id')  // Alias r ajouté ici
            ->select(
                'revendeurs.name as revendeur',
                DB::raw("COALESCE(SUM(CASE WHEN YEAR(r.created_at) = ? THEN r.prix_produit ELSE 0 END), 0) as chiffre_affaire_current_year", [$currentYear]),
                DB::raw("COALESCE(SUM(CASE WHEN YEAR(r.created_at) = ? THEN r.prix_produit ELSE 0 END), 0) as chiffre_affaire_last_year", [$lastYear])
            )
            ->addBinding($currentYear, 'select') // Ajouter l'année actuelle comme paramètre de liaison
            ->addBinding($lastYear, 'select') // Ajouter l'année précédente comme paramètre de liaison
            ->where('r.etat_red', 0)  // Utilisation correcte de l'alias r
            ->groupBy('revendeurs.id', 'revendeurs.name')
            ->orderByDesc('chiffre_affaire_current_year') // Trier les revendeurs par chiffre d'affaire de l'année courante
            ->paginate(100); // Ajout de la pagination, 10 revendeurs par page
    
        // Ajouter un rang basé sur le chiffre d'affaire en tenant compte de la page
        $revendeursData->getCollection()->transform(function ($revendeur, $index) use ($revendeursData) {
            // Calcul du rang en tenant compte de la page actuelle
            $revendeur->rank = $revendeursData->currentPage() * 10 - 10 + $index + 1;
            return $revendeur;
        });
    
        // Récupérer l'année en cours
        $currentYear = date('Y');
    
        // Requête SQL pour récupérer les chiffres d'affaires des revendeurs et marques pour l'année en cours
        $resultats = DB::table('marques')
            ->join('redirections AS r', 'marques.id', '=', 'r.brand_id')  // Alias r ajouté ici
            ->join('revendeurs', 'r.revendeur_id', '=', 'revendeurs.id')
            ->select(
                'marques.name as marque',
                'revendeurs.name as revendeur',
                DB::raw('SUM(CASE WHEN YEAR(r.created_at) = ' . $currentYear . ' THEN r.prix_produit ELSE 0 END) as chiffre_affaire')
            )
            ->where('r.etat_red', 0)  // Utilisation correcte de l'alias r
            ->groupBy('marques.id', 'revendeurs.id', 'marques.name', 'revendeurs.name')
            ->orderBy('marques.name')
            ->orderByDesc(DB::raw('SUM(r.prix_produit)')) // Tri décroissant par chiffre d'affaire
            ->get();
    
        // Regrouper les résultats par marque et ajouter un rank pour chaque revendeur
        $resultatsByMarque = [];
        foreach ($resultats as $resultat) {
            $resultatsByMarque[$resultat->marque][] = $resultat;
        }
    
        // Ajouter le rang (rank) pour chaque revendeur par marque
        foreach ($resultatsByMarque as $marque => &$revendeurs) {
            usort($revendeurs, function($a, $b) {
                return $b->chiffre_affaire <=> $a->chiffre_affaire; // Tri décroissant du chiffre d'affaire
            });
    
            // Ajouter un rang basé sur le chiffre d'affaire
            foreach ($revendeurs as $index => &$revendeur) {
                $revendeur->rank = $index + 1; // Le rang commence à 1
            }
        }
    // Calcul du total du CA des revendeurs

        // Retourner les deux ensembles de données à la vue
        return view('themes.dashboard_revendeur', compact('revendeursData', 'resultatsByMarque', 'currentYear'));
    }
    
    
    
    public function changeEtat($id)
    {
        try {
            // Récupérer le revendeur par son ID
            $revendeur = Revendeur::findOrFail($id);
    
            // Inverser l'état (1 devient 0 et 0 devient 1)
            $revendeur->etat = $revendeur->etat == 1 ? 0 : 1;
    
            // Sauvegarder les modifications
            $revendeur->save();
    
            // Rediriger avec un message de succès
            return redirect()->back()->with('success', 'État du revendeur modifié avec succès.');
        } catch (\Exception $e) {
            // Gestion des erreurs
            return redirect()->back()->with('error', 'Erreur lors du changement de l\'état : ' . $e->getMessage());
        }
    }
    

  

 }
