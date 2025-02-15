<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Commercial;
use App\Models\Produit;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\DB as FacadeDB;
use Carbon\Carbon;

use Illuminate\View\View;
use Illuminate\Support\Facades\File;

class BrandController extends Controller
{
    public function createBrand(): View
    {
        $commercials = Commercial::all();
        return view('themes.ajoutermarque', compact('commercials'));
    }

    public function createRedirection(): View
    {
        // Récupérer toutes les marques
        $brands = Brand::all();

        // Passer la liste des marques à la vue
        return view('themes.ajouterredirection', compact('brands'));
    }
    public function store(Request $request)
    { 
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'commercial_id' => 'required|exists:commercial,id',
            'id_team' => 'required|exists:team,id', // Valider que l'ID de l'équipe existe
        ]);
    
        try {
            $brand = new Brand();
            $brand->name = $request->input('name');
            $brand->commercial_id = $request->input('commercial_id');
            $brand->etat = $request->input('etat', 1);  // Utiliser 1 comme valeur par défaut si 'etat' n'est pas fourni
            $brand->id_team = $request->input('id_team');
    
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('logos'), $filename);
                $brand->logo = $filename;
            }
    
            $brand->save();
    
            return redirect()->back()->with('success', 'Marque enregistrée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement de la marque : ' . $e->getMessage());
        }
    }
    
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // validation du logo
        ]);
    
        try {
            $brand = Brand::findOrFail($id); // Trouver la marque par ID
    
            $brand->name = $request->input('name');
    
            // Vérifier si un logo est téléchargé et le déplacer
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('logos'), $filename); // Déplacement vers le répertoire public/logos
                $brand->logo = $filename; // Mettre à jour le logo
            }
    
            $brand->save(); // Sauvegarder les changements
    
            return redirect()->back()->with('success', 'Marque mise à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour de la marque : ' . $e->getMessage());
        }
    }
    

    public function index(): View
    {
        $brands = Brand::all();
        return view('themes.ajouterproduit', compact('brands'));
    }

    public function indexForBrand(): View
    {
        $brands = Brand::all();
        return view('themes.listemarque', compact('brands'));
    }
    public function indexForEvenement(): View
    {
        $brands = Brand::all();
        return view('themes.ajouterevenement', compact('brands'));
    }
    public function indexForEvenements(): View
    {
        $brands = Brand::all();
        return view('themes.listeevenement', compact('brands'));
    }
    
    public function indexForDashaboardEvenements(): View
    {
        $brands = Brand::all();
        return view('themes.dashboard_evenement', compact('brands'));
    }
    public function indexForRedirection(): View
    {
        $brands = Brand::all();
        return view('themes.ajouterredirection', compact('brands'));
    }
    public function indexForCompaigne(): View
    {
        $brands = Brand::all();
        return view('themes.ajoutercompagne', compact('brands'));
    }

    public function indexForTeam(): View
    {
        $brands = Brand::all();
        return view('themes.ajouterequipe', compact('brands'));
    }
     

     

    public function changeEtat($id)
    {
        try {
            // Récupérer la marque par son ID
            $brand = Brand::findOrFail($id);
    
            // Inverser l'état (0 devient 1 et 1 devient 0)
            $brand->etat = $brand->etat == 1 ? 0 : 1;
    
            // Sauvegarder les modifications
            $brand->save();
    
            // Rediriger avec un message de succès
            return redirect()->back()->with('success', 'État de la marque modifié avec succès.');
        } catch (\Exception $e) {
            // Gestion des erreurs
            return redirect()->back()->with('error', 'Erreur lors du changement de l\'état : ' . $e->getMessage());
        }
    }
    

    
    public function produits()
    {
        return $this->hasMany(Produit::class, 'brand_id');
    }

    public function DashboardMarque()
    {
        // Définir la date actuelle
        $currentDate = Carbon::now();
    
        // Calcul des dates pour les différentes périodes
        $startOfYear = $currentDate->startOfYear(); // Date du début de l'année
        $startOfMonth = $currentDate->startOfMonth(); // Date du début du mois
        $startOfLast7Days = $currentDate->subDays(7); // Date des 7 derniers jours
        $startOfYesterday = $currentDate->subDays(1); // Date d'hier
        $startOfLast14Days = $currentDate->subDays(14); // Date des 14 derniers jours
        $startOfLast7DaysPrevious = $startOfLast14Days->addDays(7); // Date des 7 jours avant les 7 derniers jours (DOL7D)
    
        // Exécution de la requête
        $results = DB::table('redirections')
            ->join('evenements', 'redirections.evenements_id', '=', 'evenements.id')
            ->join('marques', 'evenements.brand_id', '=', 'marques.id')
            ->join('campaigns', 'redirections.evenements_id', '=', 'campaigns.evenement_id')  // Relier les campagnes
            ->select(
                'marques.name AS marque_name',
    
                // Total Annuel (Annual)
                DB::raw('SUM(CASE WHEN redirections.created_at >= ? THEN campaigns.budget ELSE 0 END) AS total_cost_annual', [$startOfYear]),
                DB::raw('SUM(CASE WHEN redirections.created_at >= ? THEN redirections.prix_produit * 0.15 ELSE 0 END) AS total_revenue_annual', [$startOfYear]),
                DB::raw('COALESCE(SUM(CASE WHEN redirections.created_at >= ? THEN redirections.prix_produit * 0.15 ELSE 0 END), 0) / NULLIF(SUM(CASE WHEN redirections.created_at >= ? THEN campaigns.budget ELSE 0 END), 0) * 100 AS ROI_annual', [$startOfYear, $startOfYear]),
    
                // Total YTD (Year-To-Date)
                DB::raw('SUM(CASE WHEN redirections.created_at >= ? THEN campaigns.budget ELSE 0 END) AS total_cost_ytd', [$startOfYear]),
                DB::raw('SUM(CASE WHEN redirections.created_at >= ? THEN redirections.prix_produit * 0.15 ELSE 0 END) AS total_revenue_ytd', [$startOfYear]),
                DB::raw('COALESCE(SUM(CASE WHEN redirections.created_at >= ? THEN redirections.prix_produit * 0.15 ELSE 0 END), 0) / NULLIF(SUM(CASE WHEN redirections.created_at >= ? THEN campaigns.budget ELSE 0 END), 0) * 100 AS ROI_ytd', [$startOfYear, $startOfYear]),
    
                // Total MRD (Month-to-Date)
                DB::raw('SUM(CASE WHEN redirections.created_at >= ? AND redirections.created_at <= ? THEN campaigns.budget ELSE 0 END) AS total_cost_mrd', [$startOfMonth, $currentDate]),
                DB::raw('SUM(CASE WHEN redirections.created_at >= ? AND redirections.created_at <= ? THEN redirections.prix_produit * 0.15 ELSE 0 END) AS total_revenue_mrd', [$startOfMonth, $currentDate]),
                DB::raw('COALESCE(SUM(CASE WHEN redirections.created_at >= ? AND redirections.created_at <= ? THEN redirections.prix_produit * 0.15 ELSE 0 END), 0) / NULLIF(SUM(CASE WHEN redirections.created_at >= ? AND redirections.created_at <= ? THEN campaigns.budget ELSE 0 END), 0) * 100 AS ROI_mrd', [$startOfMonth, $currentDate, $startOfMonth, $currentDate]),
    
                // Total L7D (Last 7 Days)
                DB::raw('SUM(CASE WHEN redirections.created_at >= ? THEN campaigns.budget ELSE 0 END) AS total_cost_l7d', [$startOfLast7Days]),
                DB::raw('SUM(CASE WHEN redirections.created_at >= ? THEN redirections.prix_produit * 0.15 ELSE 0 END) AS total_revenue_l7d', [$startOfLast7Days]),
                DB::raw('COALESCE(SUM(CASE WHEN redirections.created_at >= ? THEN redirections.prix_produit * 0.15 ELSE 0 END), 0) / NULLIF(SUM(CASE WHEN redirections.created_at >= ? THEN campaigns.budget ELSE 0 END), 0) * 100 AS ROI_l7d', [$startOfLast7Days, $startOfLast7Days]),
    
                // Total DOD (Day-on-Day)
                DB::raw('SUM(CASE WHEN redirections.created_at >= ? AND redirections.created_at <= ? THEN campaigns.budget ELSE 0 END) AS total_cost_dod', [$startOfYesterday, $currentDate]),
                DB::raw('SUM(CASE WHEN redirections.created_at >= ? AND redirections.created_at <= ? THEN redirections.prix_produit * 0.15 ELSE 0 END) AS total_revenue_dod', [$startOfYesterday, $currentDate]),
                DB::raw('COALESCE(SUM(CASE WHEN redirections.created_at >= ? AND redirections.created_at <= ? THEN redirections.prix_produit * 0.15 ELSE 0 END), 0) / NULLIF(SUM(CASE WHEN redirections.created_at >= ? AND redirections.created_at <= ? THEN campaigns.budget ELSE 0 END), 0) * 100 AS ROI_dod', [$startOfYesterday, $currentDate, $startOfYesterday, $currentDate]),
    
                // Total DOL7D (Day-on-Last 7 Days)
                DB::raw('SUM(CASE WHEN redirections.created_at >= ? AND redirections.created_at < ? THEN campaigns.budget ELSE 0 END) AS total_cost_dol7d', [$startOfLast14Days, $startOfLast7Days]),
                DB::raw('SUM(CASE WHEN redirections.created_at >= ? AND redirections.created_at < ? THEN redirections.prix_produit * 0.15 ELSE 0 END) AS total_revenue_dol7d', [$startOfLast14Days, $startOfLast7Days]),
                DB::raw('COALESCE(SUM(CASE WHEN redirections.created_at >= ? AND redirections.created_at < ? THEN redirections.prix_produit * 0.15 ELSE 0 END), 0) / NULLIF(SUM(CASE WHEN redirections.created_at >= ? AND redirections.created_at < ? THEN campaigns.budget ELSE 0 END), 0) * 100 AS ROI_dol7d', [$startOfLast14Days, $startOfLast7Days, $startOfLast14Days, $startOfLast7Days])
            )
            ->groupBy('marques.name')
            ->get();
    
        // Retourner les résultats à la vue
        return view('themes.dashabord_marque', compact('results'));
    }
  

}
