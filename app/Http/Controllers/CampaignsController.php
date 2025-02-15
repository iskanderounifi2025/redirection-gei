<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CampaignsController extends Controller
{
    // Afficher le formulaire de création et la liste des campagnes
    public function create()
    {
        // Récupération des événements
        $evenements = Evenement::all();
    
        // Debug : Vérifier si les données existent
        if ($evenements->isEmpty()) {
            dd('Aucun événement trouvé.');
        }
        
        // Récupération des campagnes
        $compagains = Campaign::all();
    
        // Passer les données à la vue
        return view('themes.ajoutercompagne', compact('evenements', 'compagains'));
    }
    
    
    // Enregistrer une nouvelle campagne
    public function store(Request $request)
    {
        // Validation des données d'entrée
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'evenement_id' => 'required|exists:evenements,id',
            'type' => 'required|string|in:ads,influence,ugc,urbain,tv',
            'budget' => 'nullable|numeric|min:0',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'nom_influencer' => 'nullable|string|max:255',
            'plateforme' => 'nullable|string|in:facebook,instagram,tiktok',
            'montant' => 'nullable|numeric|min:0',
            'nombre_reels' => 'nullable|integer|min:0',
            'nom_ugc' => 'nullable|string|max:255',
            'montant_ugc' => 'nullable|numeric|min:0',
            'plateforme_ugc' => 'nullable|string|in:facebook,instagram,tiktok',
            'id_team'=>'required|exists:team,id',
        ]);

        // Créer une nouvelle campagne
        $campaign = new Campaign($validated);
        $campaign->save();

        // Redirection avec un message de succès
        return redirect()->route('themes.ajoutercompagne')->with('success', 'Campagne ajoutée avec succès!');
    }
    public function index(): View
    {
        // Exécuter la requête SQL avec la jointure pour obtenir le nom de la marque
        $campaigns = DB::select('
            SELECT 
                events.nom AS event_name,
                COUNT(campaigns.id) AS total_campaigns,
                SUM(COALESCE(campaigns.budget, 0)) AS total_budget,  -- Somme de budget
                SUM(COALESCE(campaigns.montant, 0)) AS total_montant,  -- Somme de montant
                SUM(COALESCE(campaigns.montant_ugc, 0)) AS total_montant_ugc,  -- Somme de montant_ugc
                SUM(COALESCE(campaigns.budget, 0) + COALESCE(campaigns.montant, 0) + COALESCE(campaigns.montant_ugc, 0)) AS total_sum,  -- Somme totale
                GROUP_CONCAT(campaigns.nom SEPARATOR ", ") AS campaign_names,
                GROUP_CONCAT(campaigns.type SEPARATOR ", ") AS campaign_type,
                GROUP_CONCAT(campaigns.budget SEPARATOR ", ") AS campaign_budget,
                GROUP_CONCAT(campaigns.date_debut SEPARATOR ", ") AS start_date,
                GROUP_CONCAT(campaigns.date_fin SEPARATOR ", ") AS end_date,
                GROUP_CONCAT(campaigns.nom_influencer SEPARATOR ", ") AS nom_influencer,
                GROUP_CONCAT(campaigns.plateforme SEPARATOR ", ") AS plateforme,
                GROUP_CONCAT(campaigns.montant SEPARATOR ", ") AS montant,
                GROUP_CONCAT(campaigns.nombre_reels SEPARATOR ", ") AS nombre_reels,
                GROUP_CONCAT(campaigns.nom_ugc SEPARATOR ", ") AS nom_ugc,
                GROUP_CONCAT(campaigns.montant_ugc SEPARATOR ", ") AS montant_ugc,
                GROUP_CONCAT(campaigns.plateforme_ugc SEPARATOR ", ") AS plateforme_ugc,
                marques.name AS marque_name  -- Ajout du nom de la marque
            FROM 
                campaigns
            JOIN 
                evenements AS events ON campaigns.evenement_id = events.id
            LEFT JOIN 
                marques ON events.brand_id = marques.id  -- Jointure avec la table marques
            GROUP BY 
                events.nom, marques.name  -- Groupement par événement et par marque
            ORDER BY 
                events.nom
        ');
    
        // Formater les dates avant de passer les données à la vue
        foreach ($campaigns as $campaign) {
            // Formater les dates de début et de fin
            $campaign->start_date = Carbon::parse($campaign->start_date)->format('d M Y');
            $campaign->end_date = Carbon::parse($campaign->end_date)->format('d M Y');
        }
    
        // Regrouper les campagnes par événement
        $groupedCampaigns = collect($campaigns)->groupBy('event_name');
    
        // Retourner la vue avec les campagnes regroupées
        return view('themes.listecompagne', compact('groupedCampaigns'));
    }
    
    
    // Éditer une campagne existante
    public function edit($id)
    {
        $campaign = Campaign::findOrFail($id); // Récupération de la campagne ou erreur 404
        return view('themes.campaign.edit', compact('campaign'));
    }

    // Supprimer une campagne
    public function destroy($id)
    {
        $campaign = Campaign::findOrFail($id); // Récupération de la campagne ou erreur 404
        $campaign->delete(); // Suppression de la campagne

        return redirect()->route('themes.ajoutercompagne')->with('success', 'Campagne supprimée avec succès.');
    }
}
