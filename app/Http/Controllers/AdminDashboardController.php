<?php

namespace App\Http\Controllers;

use App\Models\Redirection;
use App\Models\Brand;
use App\Models\Revendeur;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    // Function to estimate gender based on first name using NameAPI
    public function estimateGender($client_prenom)
    {
        if (empty($client_prenom)) {
            return null; // If the first name is empty, return null
        }

        $client = new Client();

        try {
            // Send the request to the NameAPI API
            $response = $client->get('https://api.nameapi.org/rest/v1/genderize', [
                'query' => ['name' => $client_prenom],
                'headers' => [
                    'Authorization' => '979485af16293f6f1fc9c61d80e18bad-user1', // Replace with your NameAPI API key
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            // Check if the "gender" field exists in the API response
            return $data['gender'] ?? null; // Returns "male", "female", or null
        } catch (\Exception $e) {
            \Log::error("Error with NameAPI: " . $e->getMessage());
            return null;
        }
    }

    // Dashboard function to gather and pass data to the view
    public function index()
    {
        $currentYear = Carbon::now()->year;
        $lastYear = Carbon::now()->subYear()->year;
    
        // Fetching brands with sales figures for the current and last year
        $allMarques = DB::table('marques')
            ->leftJoin('redirections', 'marques.id', '=', 'redirections.brand_id')
            ->select(
                'marques.name as marque',
                DB::raw("SUM(CASE WHEN YEAR(redirections.created_at) = {$currentYear} THEN redirections.prix_produit ELSE 0 END) as chiffre_affaire_current_year"),
                DB::raw("SUM(CASE WHEN YEAR(redirections.created_at) = {$lastYear} THEN redirections.prix_produit ELSE 0 END) as chiffre_affaire_last_year")
            )
            ->where('etat_red', 0)
            ->groupBy('marques.id', 'marques.name')
            ->get();
    
        // Redirections for the current and last year
        $totalRedirectionsCurrentYear = Redirection::whereYear('created_at', $currentYear)
        ->where('etat_red', 0)
            ->distinct('reference')
            ->count('reference');
        $totalRedirectionsLastYear = Redirection::whereYear('created_at', $lastYear)
        ->where('etat_red', 0)
            ->distinct('reference')
            ->count('reference');
    
        // Comparisons
        $augmentationRedirections = $totalRedirectionsCurrentYear - $totalRedirectionsLastYear;
        $chiffreAffaireCurrentYear = Redirection::whereYear('created_at', $currentYear)
        ->where('etat_red', 0)
            ->sum('prix_produit');
        $chiffreAffaireLastYear = Redirection::whereYear('created_at', $lastYear)
        ->where('etat_red', 0)
            ->sum('prix_produit');
        $augmentationChiffreAffaire = $chiffreAffaireCurrentYear - $chiffreAffaireLastYear;
    
        // Monthly redirections for both current and last year
        $redirectionsParMoisCurrentYear = Redirection::whereYear('created_at', $currentYear)
            ->select(DB::raw('MONTH(created_at) as mois'), DB::raw('COUNT(*) as total'))
            ->where('etat_red', 0)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();
        $redirectionsParMoisLastYear = Redirection::whereYear('created_at', $lastYear)
            ->select(DB::raw('MONTH(created_at) as mois'), DB::raw('COUNT(*) as total'))
            ->where('etat_red', 0)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();
    
        // Traffic sources (grouped by source_red)
        $trafficSources = DB::table('redirections')
            ->select('source_red', DB::raw('count(*) as total'))
            ->where('etat_red', 0)
            ->groupBy('source_red')
            ->orderBy('total', 'desc')
            ->get();
    
        // Fetching sales data for retailers
        $revendeursData = DB::table('revendeurs')
            ->leftJoin('redirections', 'revendeurs.id', '=', 'redirections.revendeur_id')
            ->select(
                'revendeurs.name as revendeur',
                DB::raw("SUM(CASE WHEN YEAR(redirections.created_at) = {$currentYear} THEN redirections.prix_produit ELSE 0 END) as chiffre_affaire_current_year"),
                DB::raw("SUM(CASE WHEN YEAR(redirections.created_at) = {$lastYear} THEN redirections.prix_produit ELSE 0 END) as chiffre_affaire_last_year")
            )
            ->where('etat_red', 0)
            ->groupBy('revendeurs.id', 'revendeurs.name')
            ->get();
    
        // Statistics by sex for current and last year
        $sexStatistics = DB::table('redirections')
            ->select(
                'sexe',
                DB::raw("SUM(CASE WHEN YEAR(created_at) = {$currentYear} THEN 1 ELSE 0 END) as total_current_year"),
                DB::raw("SUM(CASE WHEN YEAR(created_at) = {$lastYear} THEN 1 ELSE 0 END) as total_last_year")
            )
            ->where('etat_red', 0)
            ->groupBy('sexe')
            ->get();
    
        // Data to pass to the view
        $data = [
            'totalRedirectionsCurrentYear' => $totalRedirectionsCurrentYear,
            'totalRedirectionsLastYear' => $totalRedirectionsLastYear,
            'augmentationRedirections' => $augmentationRedirections,
            'chiffreAffaireCurrentYear' => $chiffreAffaireCurrentYear,
            'chiffreAffaireLastYear' => $chiffreAffaireLastYear,
            'augmentationChiffreAffaire' => $augmentationChiffreAffaire,
            'redirectionsParMoisCurrentYear' => $redirectionsParMoisCurrentYear,
            'redirectionsParMoisLastYear' => $redirectionsParMoisLastYear,
            'allMarques' => $allMarques,
            'trafficSources' => $trafficSources,
            'revendeursData' => $revendeursData,
            'sexStatistics' => $sexStatistics,
            'currentYear' => $currentYear,
            'lastYear' => $lastYear,
        ];
    
        return view('themes.admin.dashboard', compact('data'));
    }
    

    public function getClientSexStatistics()
    {
        // Année en cours et année précédente
        $currentYear = now()->year;
        $lastYear = $currentYear - 1;
    
        // Requête pour récupérer les statistiques
        $statistics = DB::table('redirections')
            ->select(
                DB::raw("YEAR(created_at) as year"),
                'sexe',
                DB::raw("COUNT(*) as count")
            )
            ->whereIn(DB::raw("YEAR(created_at)"), [$currentYear, $lastYear])
            ->groupBy('year', 'sexe')
            ->get();
    
        // Formater les données pour l'affichage
        $data = [
            'currentYear' => [
                'Homme' => $statistics->where('year', $currentYear)->where('sexe', 'Homme')->first()->count ?? 0,
                'Femme' => $statistics->where('year', $currentYear)->where('sexe', 'Femme')->first()->count ?? 0,
            ],
            'lastYear' => [
                'Homme' => $statistics->where('year', $lastYear)->where('sexe', 'Homme')->first()->count ?? 0,
                'Femme' => $statistics->where('year', $lastYear)->where('sexe', 'Femme')->first()->count ?? 0,
            ],
        ];
    
        // Retourner la vue avec les données
        return view('themes.admin.dashboard', compact('data', 'currentYear', 'lastYear'));
    }


    // Function for weekly redirection dashboard
    public function DashbaordSemainRedirection()
    {
        $currentWeek = Carbon::now()->weekOfYear;
        $lastWeek = Carbon::now()->subWeek()->weekOfYear;

        // Fetching brands with sales figures for the current and last week
        $allMarques = DB::table('marques')
            ->leftJoin('redirections', 'marques.id', '=', 'redirections.brand_id')
            ->select(
                'marques.name as marque',
                DB::raw("SUM(CASE WHEN WEEKOFYEAR(redirections.created_at) = {$currentWeek} THEN redirections.prix_produit ELSE 0 END) as chiffre_affaire_current_week"),
                DB::raw("SUM(CASE WHEN WEEKOFYEAR(redirections.created_at) = {$lastWeek} THEN redirections.prix_produit ELSE 0 END) as chiffre_affaire_last_week")
            )
            ->groupBy('marques.id', 'marques.name')
            ->get();

        // Redirections for the current and last week
        $totalRedirectionsCurrentWeek = Redirection::whereRaw('WEEKOFYEAR(created_at) = ?', [$currentWeek])
            ->distinct('reference')
            ->count('reference');
        $totalRedirectionsLastWeek = Redirection::whereRaw('WEEKOFYEAR(created_at) = ?', [$lastWeek])
            ->distinct('reference')
            ->count('reference');

        // Comparisons
        $augmentationRedirections = $totalRedirectionsCurrentWeek - $totalRedirectionsLastWeek;
        $chiffreAffaireCurrentWeek = Redirection::whereRaw('WEEKOFYEAR(created_at) = ?', [$currentWeek])
            ->sum('prix_produit');
        $chiffreAffaireLastWeek = Redirection::whereRaw('WEEKOFYEAR(created_at) = ?', [$lastWeek])
            ->sum('prix_produit');
        $augmentationChiffreAffaire = $chiffreAffaireCurrentWeek - $chiffreAffaireLastWeek;

        // Traffic sources (grouped by source_red)
        $trafficSources = DB::table('redirections')
            ->whereRaw('WEEKOFYEAR(created_at) IN (?, ?)', [$currentWeek, $lastWeek])
            ->select('source_red', DB::raw('count(*) as total'))
            ->groupBy('source_red')
            ->orderBy('total', 'desc')
            ->get();

        // Fetching sales data for retailers
        $revendeursData = DB::table('revendeurs')
            ->leftJoin('redirections', 'revendeurs.id', '=', 'redirections.revendeur_id')
            ->select(
                'revendeurs.name as revendeur',
                DB::raw("SUM(CASE WHEN WEEKOFYEAR(redirections.created_at) = {$currentWeek} THEN redirections.prix_produit ELSE 0 END) as chiffre_affaire_current_week"),
                DB::raw("SUM(CASE WHEN WEEKOFYEAR(redirections.created_at) = {$lastWeek} THEN redirections.prix_produit ELSE 0 END) as chiffre_affaire_last_week")
            )
            ->groupBy('revendeurs.id', 'revendeurs.name')
            ->get();

        // Data to pass to the view
        $data = [
            'totalRedirectionsCurrentWeek' => $totalRedirectionsCurrentWeek,
            'totalRedirectionsLastWeek' => $totalRedirectionsLastWeek,
            'augmentationRedirections' => $augmentationRedirections,
            'chiffreAffaireCurrentWeek' => $chiffreAffaireCurrentWeek,
            'chiffreAffaireLastWeek' => $chiffreAffaireLastWeek,
            'augmentationChiffreAffaire' => $augmentationChiffreAffaire,
            'allMarques' => $allMarques,
            'trafficSources' => $trafficSources,
            'revendeursData' => $revendeursData,
            'currentWeek' => $currentWeek,
            'lastWeek' => $lastWeek,
        ];

        return view('themes/tv/dashaboard', compact('data'));
    }
}
