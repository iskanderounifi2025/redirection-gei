<?php

namespace App\Http\Controllers;
use App\Models\Produit;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
 
use Illuminate\Support\Facades\DB as FacadeDB;
use Illuminate\View\View;
use Carbon\Carbon; // Utilisation correcte de la classe Carbon
 
 /**
     * Récupère les produits associés à une marque spécifique.
     *
     * @param  int  $brandId
     * @return \Illuminate\Http\JsonResponse
     */
class ProduitController extends Controller
{
    /**
     * Affiche la liste des produits.
     */
    
     public function index(): View
     {
         // Récupère tous les produits avec leur marque associée, en paginant les résultats
         $produits = Produit::with('brand')->paginate(1500); // Paginate the results with 10 per page
         
         return view('themes.listeproduit', compact('produits'));
     }
     
     public function recherche(Request $request)
{
    $query = $request->input('query');
    $produits = Produit::where('name', 'like', "%{$query}%")
                ->orWhere('sku', 'like', "%{$query}%")
                ->get();

    return response()->json($produits);
}

     
    /**
     * Affiche la liste des produits pour la redirection.
     */
    public function indexForProduitRedirection(): View
    {
        $produits = Produit::with('brand')->get();
        return view('themes.ajouterredirection', compact('produits'));
    }

    /**
     * Stocke un nouveau produit dans la base de données.
     */
    public function store(Request $request)
    {
        // Validation des champs du formulaire
        $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|integer',
            'sku' => 'required|string|max:100',
            'price' => 'required|numeric',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id_team' => 'required|exists:team,id', // Valider que l'ID de l'équipe existe

        ]);

        // Gestion de l'upload d'image
        $imagePath = null;
        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('produit', 'public');
        }

        // Création d'un nouveau produit
        Produit::create([
            'name' => $request->input('name'),
            'brand_id' => $request->input('brand_id'),
            'sku' => $request->input('sku'),
            'etat' => $request->input('etat', 1), // Valeur par défaut de 1 si 'etat' n'est pas fourni
            'price' => $request->input('price'),
            'image_path' => $imagePath,
            'id_team' => $request->input('id_team'),

        ]);

        return redirect()->back()->with('success', 'Produit ajouté avec succès !');
    }
    // Méthode pour afficher le formulaire d'édition
    public function edit($id)
    {
        // Trouver le produit par son ID
        $produit = Produit::findOrFail($id);

        // Retourner la vue avec le produit à modifier
        return view('themes.listeproduit', compact('produit'));
    }

    // Méthode pour mettre à jour un produit
    public function update(Request $request, $id)
    {
        // Validation des données du formulaire
        $request->validate([
            'sku' => 'required|string|max:100',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'etat' => 'nullable|boolean',
        ]);

        // Trouver le produit par son ID
        $produit = Produit::findOrFail($id);

        // Gestion de l'upload de l'image
        $imagePath = $produit->image_path; // Conserver l'ancien chemin de l'image
        if ($request->hasFile('image_path')) {
            // Supprimer l'ancienne image si elle existe
            if ($produit->image_path) {
                Storage::disk('public')->delete($produit->image_path);
            }
            // Enregistrer la nouvelle image
            $imagePath = $request->file('image_path')->store('produit', 'public');
        }

        // Mise à jour de l'état (checkbox) : Si l'option est activée, l'état devient 1, sinon 0
        $etat = $request->has('etat') && $request->etat === 'on' ? 1 : 0;

        // Mise à jour du produit avec les nouvelles données
        $produit->update([
            'sku' => $request->input('sku'),
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'image_path' => $imagePath,
            'etat' => $etat,
        ]);

        // Rediriger avec un message de succès
        return redirect()->route('produits.index')->with('success', 'Produit mis à jour avec succès');
    }
    

    public function updateEtat(Request $request, $id)
    {
        // Trouver le produit par son ID
        $produit = Produit::findOrFail($id);
    
        // Vérifier la valeur de 'etat' et la convertir en un entier
        $etat = ($request->has('etat') && $request->etat === 'on') ? 1 : 0;
    
        // Mettre à jour l'état du produit
        $produit->etat = $etat;
        $produit->save();
    
        // Retourner une réponse (peut être rediriger ou envoyer un message de succès)
        return redirect()->back()->with('success', 'L\'état du produit a été mis à jour.');
    }
    

    
    /**
     * Recherche un produit par SKU.
     */
    public function searchSku(Request $request)
    {
        // Validation du champ de recherche
        $request->validate([
            'sku' => 'required|string|max:255',
        ]);

        $sku = $request->input('sku');
        $products = Produit::where('sku', 'like', '%' . $sku . '%')->get(['sku', 'name', 'price']);
        
        return response()->json($products);
    }
    
    public function getProductsByBrand(Request $request)
    {
        // Effectuer la requête SQL pour récupérer les produits d'une marque
        $products = DB::table('marques')
            ->join('produit', 'marques.id', '=', 'produit.brand_id')
            ->select('produit.id', 'produit.name', 'produit.sku', 'produit.price')
            ->where('marques.id', $request->brand_id)
            ->orderBy('marques.name')
            ->orderBy('produit.name')
            ->get();
    
        // Retourner les produits sous forme de JSON
        return response()->json(['products' => $products]);
    }
    
    public function dashboardProduit()
    {
        $currentYear = now()->year; // Année en cours
        $lastYear = $currentYear - 1; // Année précédente
    
        // Récupérer le chiffre d'affaires total pour chaque produit pour l'année actuelle avec pagination
        $currentYearData = DB::table('redirections as r')
            ->select('p.sku', DB::raw('SUM(r.prix_produit) as total_chiffre_affaires'))
            ->join('produit as p', 'r.product_id', '=', 'p.id')
            ->where('r.etat_red', 0)
            ->whereYear('r.created_at', $currentYear)
            ->groupBy('p.sku')
            ->orderByDesc('total_chiffre_affaires')
            ->paginate(10); // Pagination avec 10 résultats par page
    
        // Récupérer le chiffre d'affaires total pour chaque produit pour l'année précédente avec pagination
        $lastYearData = DB::table('redirections as r')
            ->select('p.sku', DB::raw('SUM(r.prix_produit) as total_chiffre_affaires'))
            ->join('produit as p', 'r.product_id', '=', 'p.id')
            ->where('r.etat_red', 0)
            ->whereYear('r.created_at', $lastYear)
            ->groupBy('p.sku')
            ->orderByDesc('total_chiffre_affaires')
            ->paginate(10); // Pagination avec 10 résultats par page
    
        // SKU les plus vendus (Top 100)
        $topSkus = DB::table('redirections as r')
            ->select('p.sku', DB::raw('SUM(r.prix_produit) as total_chiffre_affaires'))
            ->join('produit as p', 'r.product_id', '=', 'p.id')
            ->where('r.etat_red', 0)
            ->whereYear('r.created_at', $currentYear)
            ->groupBy('p.sku')
            ->orderByDesc('total_chiffre_affaires')
            ->paginate(10); // Pagination ici, 10 produits par page
    
        // SKU les moins vendus (Bottom 100)
        $bottomSkus = DB::table('redirections as r')
            ->select('p.sku', DB::raw('SUM(r.prix_produit) as total_chiffre_affaires'))
            ->join('produit as p', 'r.product_id', '=', 'p.id')
            ->where('r.etat_red', 0)
            ->whereYear('r.created_at', $currentYear)
            ->groupBy('p.sku')
            ->orderBy('total_chiffre_affaires', 'asc') // Trier par chiffre d'affaires croissant
            ->paginate(10); // Pagination ici, 10 produits par page
    
        // Passer les données à la vue
        return view('themes.dashboard_produit', compact('currentYearData', 'lastYearData', 'topSkus', 'bottomSkus', 'currentYear', 'lastYear'));
    }

    

    
    
    
    
    
}