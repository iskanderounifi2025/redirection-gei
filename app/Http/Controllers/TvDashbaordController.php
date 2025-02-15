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

class TvDashbaordController extends Controller
{
    public function index()
    {
        $currentDate = Carbon::now();
        $lastWeekDate = Carbon::now()->subWeek();
    
        // Define start of the week (Monday) and end of the week (Sunday)
        $startOfWeekCurrent = $currentDate->copy()->startOfWeek();
        $endOfWeekCurrent = $startOfWeekCurrent->copy()->endOfWeek();
        $startOfWeekLast = $lastWeekDate->copy()->startOfWeek();
        $endOfWeekLast = $startOfWeekLast->copy()->endOfWeek();
    
        // Fetching brands with sales figures for the current and last week
        $allMarques = DB::table('marques')
            ->leftJoin('redirections', 'marques.id', '=', 'redirections.brand_id')
            ->select(
                'marques.name as marque',
                DB::raw("SUM(CASE WHEN redirections.created_at BETWEEN '{$startOfWeekCurrent}' AND '{$endOfWeekCurrent}' AND redirections.etat_red = 0 THEN redirections.prix_produit ELSE 0 END) as chiffre_affaire_current_week"),
                DB::raw("SUM(CASE WHEN redirections.created_at BETWEEN '{$startOfWeekLast}' AND '{$endOfWeekLast}' AND redirections.etat_red = 0 THEN redirections.prix_produit ELSE 0 END) as chiffre_affaire_last_week")
            )
            ->groupBy('marques.id', 'marques.name')
            ->get();
    
        // Redirections for the current and last week with `etat_red = 2`
        $totalRedirectionsCurrentWeek = Redirection::whereBetween('created_at', [$startOfWeekCurrent, $endOfWeekCurrent])
            ->where('etat_red', 0) // Filtre uniquement pour `etat_red = 2`
            ->distinct('reference')
            ->count('reference');
        $totalRedirectionsLastWeek = Redirection::whereBetween('created_at', [$startOfWeekLast, $endOfWeekLast])
            ->where('etat_red', 0) // Filtre uniquement pour `etat_red = 2`
            ->distinct('reference')
            ->count('reference');
    
        // Notifications (redirections non validées depuis plus de 30 minutes avec `etat_red`)
        $notifications = DB::table('redirections')
            ->select('reference', DB::raw('COUNT(*) as notification_count'), 'etat_red')
            ->whereIn('etat_red', [1, 2])  // Nous filtrons pour les etats 1 et 2
            ->where('created_at', '<', now()->subMinutes(30))
            ->groupBy('reference', 'etat_red')  // Grouper par référence et par état
            ->orderByDesc('notification_count')
            ->get();
    
        // Comparisons
        $augmentationRedirections = $totalRedirectionsCurrentWeek - $totalRedirectionsLastWeek;
        $chiffreAffaireCurrentWeek = Redirection::whereBetween('created_at', [$startOfWeekCurrent, $endOfWeekCurrent])
            ->where('etat_red', 0) // Filtre uniquement pour `etat_red = 2`
            ->sum('prix_produit');
        $chiffreAffaireLastWeek = Redirection::whereBetween('created_at', [$startOfWeekLast, $endOfWeekLast])
            ->where('etat_red', 0) // Filtre uniquement pour `etat_red = 2`
            ->sum('prix_produit');
        $augmentationChiffreAffaire = $chiffreAffaireCurrentWeek - $chiffreAffaireLastWeek;
    
        // Traffic sources for the current and last week with `etat_red = 2`
        $trafficSources = DB::table('redirections')
            ->whereBetween('created_at', [$startOfWeekCurrent, $endOfWeekCurrent])
            ->orWhereBetween('created_at', [$startOfWeekLast, $endOfWeekLast])
            ->where('etat_red', 0) // Filtre uniquement pour `etat_red = 2`
            ->select('source_red', DB::raw('COUNT(DISTINCT reference) as total'))
            ->groupBy('source_red')
            ->orderBy('total', 'desc')
            ->get();
    
        // Fetching sales data for retailers during the current and last week
        $revendeursData = DB::table('revendeurs')
            ->leftJoin('redirections', 'revendeurs.id', '=', 'redirections.revendeur_id')
            ->select(
                'revendeurs.name as revendeur',
                DB::raw("SUM(CASE WHEN redirections.created_at BETWEEN '{$startOfWeekCurrent}' AND '{$endOfWeekCurrent}' AND redirections.etat_red = 0 THEN redirections.prix_produit ELSE 0 END) as chiffre_affaire_current_week"),
                DB::raw("SUM(CASE WHEN redirections.created_at BETWEEN '{$startOfWeekLast}' AND '{$endOfWeekLast}' AND redirections.etat_red = 0 THEN redirections.prix_produit ELSE 0 END) as chiffre_affaire_last_week")
            )
            ->groupBy('revendeurs.id', 'revendeurs.name')
            ->get();
    
        // Pass the data to the view
        return view('themes.tv.dashboard', compact(
            'totalRedirectionsCurrentWeek',
            'totalRedirectionsLastWeek',
            'augmentationRedirections',
            'chiffreAffaireCurrentWeek',
            'chiffreAffaireLastWeek',
            'augmentationChiffreAffaire',
            'allMarques',
            'trafficSources',
            'revendeursData',
            'notifications' // Pass the notifications to the view
        ));
    }
    
}
