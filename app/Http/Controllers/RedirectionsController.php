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
use App\Models\EmailEnvoye;
use App\Models\Evenement;
use App\Models\Equipe;
use Illuminate\Support\Facades\Mail;
use App\Mail\RedirectionCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB as FacadeDB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; 
 // Exemple pour l'authentification
use App\Mail\RappelRedirectionMail;  // Créez une classe Mailable
 class RedirectionsController extends Controller
 {
     // Affiche le formulaire pour créer une nouvelle redirection
     public function create()
     {
        // Récupérer tous les revendeurs
        $revendeurs = Revendeur::all();
        
        // Passer les revendeurs à la vue 'themes.ajouterredirection'


        $brands = Brand::all();

        $evenements = Evenement::all();

        
        // Passer les revendeurs à la vue 'themes.ajouterredirection'
        return view('themes.ajouterredirection', compact('revendeurs', 'brands','evenements',));
    }
     
 
    // Récupérer les produits d'une marque
    
 

    public function store(Request $request)
    {
        // Validation des données de la requête
        $request->validate([
            'brand_id' => 'required|integer',
            'reference' => 'nullable|string',
            'product_id' => 'required|array|min:1|max:100',
            'nom_produit' => 'required|array|min:1|max:100',
            'qts_produit'=>'required|array|min:1|max:100',
            'prix_intial' => 'required|array|min:1|max:100',
            'timber_fiscal' => 'nullable|integer',
'frais_laivraison' => 'nullable|integer',
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
            'id_team' => 'required|exists:team,id',

        ]);
    
        // Récupération des données de la requête
        $data = $request->all();
    
        // Valeurs par défaut si non spécifiées
        $data['etat_red'] = $data['etat_red'] ?? 1;  // Par défaut, l'état de la redirection est 1
        $data['reference'] = $data['reference'] ?? 'REF' . now()->timestamp . mt_rand(100, 999);  // Générer une référence unique
    
        // Données communes à toutes les redirections
        $commonData = [
            'brand_id' => $data['brand_id'],
            'timber_fiscal' => $data['timber_fiscal'],
            'frais_laivraison' => $data['frais_laivraison'],
            'client_nom' => $data['client_nom'],
            'client_prenom' => $data['client_prenom'],
            'client_email' => $data['client_email'],
            'client_phone' => $data['client_phone'],
            'client_adresse' => $data['client_adresse'],
            'revendeur_id' => $data['revendeur_id'],
            'date_naissance_client' => $data['date_naissance_client'],
            'sexe' => $data['sexe'],
            'etat_red' => $data['etat_red'],
            'source_red' => $data['source_red'],
            'reference' => $data['reference'],
            'evenements_id' => $data['evenements_id'],
            'id_team' => $data['id_team'],

        ];
    
        // Démarrer une transaction pour l'insertion des données
        DB::transaction(function () use ($data, $commonData) {
            foreach ($data['product_id'] as $index => $productId) {
                // Créer la redirection pour chaque produit
                $redirection = Redirection::create(array_merge($commonData, [
                    'product_id' => $productId,
                    'nom_produit' => $data['nom_produit'][$index],
                    'prix_produit' => $data['prix_produit'][$index],
                    'qts_produit' => $data['qts_produit'][$index],
                    'prix_intial' => $data['prix_intial'][$index],
                    'reduction_produit' => $data['reduction_produit'][$index],
                ]));
    
                // Envoi des e-mails de notification
                try {
                    $this->sendEmails($redirection);
                } catch (\Exception $e) {
                    // Si l'envoi d'e-mail échoue, loguer l'erreur et continuer
                    Log::error("Erreur lors de l'envoi d'e-mail pour la redirection ID {$redirection->id}: " . $e->getMessage());
                }
            }
        });
    
        // Redirection avec message de succès
        return redirect()->route('themes.ajouterredirection')->with('success', 'Redirection créée avec succès.');
    }

    
    
    public function sendEmails(Redirection $redirection)
    {
        // Récupérer les détails du produit via Eloquent
        $productDetails = DB::table('redirections as r')
            ->join('produit as p', 'r.product_id', '=', 'p.id')
            ->where('r.reference', $redirection->reference)
            ->select(
                'p.sku',
                'p.name',
                'r.prix_produit',
                'r.reduction_produit',
                'r.created_at',
                'r.timber_fiscal',
                'r.frais_laivraison',
                'r.qts_produit'
            )
            ->get();
    
        // Récupérer les informations du revendeur
        $revendeur = Revendeur::find($redirection->revendeur_id);
        if (!$revendeur) {
            return response()->json(['message' => 'Revendeur non trouvé.'], 404);
        }
    
        // Récupérer les informations du commercial et de la direction
        $commercial = Commercial::find($revendeur->commercial_id);
        $direction = Direction::find($revendeur->direction_id);
    
        // Vérifier si le commercial et la direction existent
        if (!$commercial || !$direction) {
            return response()->json(['message' => 'Commercial ou Direction non trouvé(e).'], 404);
        }
    
        // Récupérer les informations de l'équipe
        $equipe = Equipe::find($redirection->id_team);
        if (!$equipe) {
            return response()->json(['message' => 'Équipe non trouvée.'], 404);
        }
    
        // Construire la liste des destinataires
        $revendeurEmails = array_filter([$revendeur->email, $revendeur->email_2]);
        $allEmails = array_filter(array_merge(
            $revendeurEmails,
            [$commercial->email ?? null, $direction->email ?? null]
        ));
    
        // Valider les emails
        $validatedEmails = array_filter($allEmails, function ($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        });
    
        if (empty($validatedEmails)) {
            return response()->json(['message' => 'Aucun email valide trouvé pour l\'envoi.'], 400);
        }
    
        $teamEmail = $equipe->email;
        $marktingEmail = 'marketing@gei.tn';
    
        // Capture le contenu HTML de l'email avant l'envoi
        $emailContent = view('themes.redirection_created', [
            'redirection' => $redirection,
            'productDetails' => $productDetails,
        ])->render();
    
        try {
            // Envoyer l'email
            Mail::send('themes.redirection_created', [
                'redirection' => $redirection,
                'productDetails' => $productDetails,
            ], function ($message) use ($validatedEmails, $teamEmail, $redirection, $marktingEmail) {
                $message->from($teamEmail, 'Redirection GEI')
                        ->to($validatedEmails)
                        ->cc($marktingEmail)
                        ->subject("Nouvelle Redirection - " . $redirection->reference);
            });
    
            // Enregistrer l'e-mail envoyé
            EmailEnvoye::create([
                'reference' => $redirection->reference,
                'destinataires' => json_encode($validatedEmails),
                'email_expediteur' => $teamEmail,
                'sujet' => "Nouvelle Redirection - " . $redirection->reference,
                'contenu' => $emailContent, // Contenu réel de l'email
            ]);
    
            return response()->json(['message' => 'E-mails envoyés et enregistrés avec succès.']);
        } catch (\Throwable $e) {
            // Log de l'erreur
            \Log::error('Erreur lors de l\'envoi des e-mails ou de l\'enregistrement', [
                'error' => $e->getMessage(),
            ]);
    
            return response()->json([
                'message' => 'Erreur lors de l\'envoi ou de l\'enregistrement des e-mails.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    

    public function listEmails()
    {
        // Récupérer tous les emails envoyés en ordre décroissant
        $emailsEnvoyes = EmailEnvoye::orderBy('created_at', 'desc')->get();
    
        // Retourner la vue avec les emails
        return view('themes.emails-redirections', compact('emailsEnvoyes'));
    }
    

public function index()
{
    // Récupère tous les revendeurs
    $revendeurs = Revendeur::all(); 
    
    // Récupérer les redirections avec les informations nécessaires et ajouter la pagination
    $redirections = DB::table('redirections as r')
    ->join('produit as p', 'r.product_id', '=', 'p.id')
    ->join('revendeurs as v', 'r.revendeur_id', '=', 'v.id')
    ->join('team as e', 'r.id_team', '=', 'e.id')
    ->select(
        'r.reference as ref_redirection',
        DB::raw("GROUP_CONCAT(CONCAT(p.name, ' (', r.prix_produit, ' TND)') ORDER BY p.name SEPARATOR ', ') as produits_avec_prix"),
        'r.client_nom',
        'r.client_prenom',
        'r.client_email',
        'r.client_phone',
        'r.client_adresse',
        'r.sexe',
        'r.date_naissance_client',
        'r.frais_laivraison',
        'r.timber_fiscal',
        'r.source_red',

        DB::raw('SUM(r.prix_produit * (1 - r.reduction_produit / 100)) as total_commande'),
        'v.name as revendeur_nom',
        'e.nometprenom as equipe_nom',
        'e.image as equipe_image',  // Si la colonne image existe
        'r.created_at as date_premiere_redirection', // Date de création simple
        'r.updated_at as date_derniere_redirection', // Date de mise à jour simple

       
        
        'r.etat_red',
        DB::raw("CASE 
                    WHEN r.etat_red = 0 THEN 'Annulé'
                    WHEN r.etat_red = 1 THEN 'Envoyé au revendeur'
                    WHEN r.etat_red = 2 THEN 'Validé, envoyé au client'
                    WHEN r.etat_red = 3 THEN 'Renvoyé'
                    ELSE 'Non défini'
                END as etat_redirection")
    )
    ->groupBy(
        'r.reference',
        'r.client_nom',
        'r.client_prenom',
        'r.client_email',
        'r.client_phone',
        'r.client_adresse',
        'v.name',
        'e.nometprenom', 
        'r.etat_red',
        'r.sexe',
        'r.date_naissance_client',
        'r.frais_laivraison',
        'r.timber_fiscal',
        'r.source_red',
        'e.image', 
        'r.created_at', // Ajouté
        'r.updated_at' , // Ajouté
        // Assurez-vous que cette colonne est présente
    )
    ->orderBy('r.created_at', 'DESC')
    ->paginate(100);

    // Passer les données à la vue
    return view('themes.listeredirection', compact('redirections', 'revendeurs'));
}


     public function indexofcart(): View
     {
         // Récupérer les adresses des clients depuis la base de données
         $redirections = Redirection::select('client_adresse')->get();

         // Utiliser l'API Nominatim d'OpenStreetMap pour récupérer les coordonnées
         $client = new Client();
         $addresses = [];
 
         foreach ($redirections as $redirection) {
             $address = $redirection->client_adresse;
             $response = $client->get('https://nominatim.openstreetmap.org/search', [
                 'query' => [
                     'q' => $address,
                     'format' => 'json',
                     'addressdetails' => 1,
                 ]
             ]);
 
             $data = json_decode($response->getBody(), true);
             if (!empty($data)) {
                 $coordinates = $data[0];  // Le premier résultat retourné par Nominatim
                 $addresses[] = [
                     'adresse' => $address,
                     'lat' => $coordinates['lat'],
                     'lng' => $coordinates['lon'],
                 ];
             }
         }
 
      
         // Passer les données à la vue
         return view('themes.cartechaleur', compact('addresses'));
     }
   
       
     // Affiche une redirection spécifique
     public function show($id)
     {
         $redirection = Redirection::findOrFail($id);
         return view('redirections.show', compact('redirection'));
     }
 
     // Affiche le formulaire pour éditer une redirection
     public function edit($id)
     {
         $redirection = Redirection::find($id);  // Utilisation d'Eloquent pour récupérer la redirection
     
         if (!$redirection) {
             return redirect()->route('redirections.index')->with('error', 'Redirection non trouvée');
         }
     
         return view('themes.listeredirection', compact('redirection'));
     }
 
     public function update(Request $request)
     {
         // Valider l'entrée
         $validated = $request->validate([
             'reference' => 'required|string',
             'etat_red' => 'required|integer',
             'revendeur_id' => 'required|exists:revendeurs,id',  // Valider que l'ID du revendeur existe
         ]);
     
         // Trouver la redirection par la référence et mettre à jour
         $redirection = Redirection::where('reference', $validated['reference'])->first();
     
         if ($redirection) {
             $redirection->etat_red = $validated['etat_red'];
             $redirection->revendeur_id = $validated['revendeur_id'];  // Mise à jour du revendeur
             $redirection->save();
     
             return response()->json(['success' => true]);
         }
     
         return response()->json(['success' => false, 'message' => 'Redirection introuvable']);
     }
     

 
     // Supprime une redirection spécifique
     public function destroy($id)
     {
         $redirection = Redirection::findOrFail($id);
         $redirection->delete();
 
         return redirect()->route('redirections.index')->with('success', 'Redirection supprimée avec succès.');
     }
 
     public function createRedirection()
     {
         $brands = Brand::all();
         $produits = Produit::with('brand')->get();
 
         return view('themes.ajouterredirection', compact('brands', 'produits'));
     }
 // Méthode pour envoyer des e-mails
 

  

  
     public function showByReference($reference)
     {
         $redirections = Redirection::where('reference', $reference)
             ->with('produit')
             ->get();
 
         if ($redirections->isEmpty()) {
             return redirect()->route('themes.listeredirection')->with('error', 'Aucune redirection trouvée pour cette référence.');
         }
 
         return view('themes.redirections_by_reference', compact('redirections'));
     }
     
 public function statistiquesMarquesDashboard()
{
    // Récupérer l'année en cours dynamiquement
    $anneeEnCours = date('Y'); // Utilise la fonction PHP pour obtenir l'année actuelle

    // Récupérer les données YTD
    $chiffreAffairesYTD = FacadeDB::select(
        'SELECT b.name AS marques, SUM(r.prix_produit) AS total_chiffre_affaires 
         FROM redirections r 
         JOIN marques b ON r.brand_id = b.id 
         WHERE YEAR(r.created_at) = ?    AND r.etat_red = 0

         GROUP BY r.brand_id, b.name
         ORDER BY total_chiffre_affaires DESC',
        [$anneeEnCours] // Paramètre dynamique pour l'année
    );

    // Récupérer les données MTD
    $chiffreAffairesMTD = FacadeDB::select(
        'SELECT b.name AS marques, 
                DATE(r.created_at) AS jour, 
                SUM(r.prix_produit) AS total_chiffre_affaires 
         FROM redirections r 
         JOIN marques b ON r.brand_id = b.id 
         WHERE MONTH(r.created_at) = MONTH(CURRENT_DATE()) 
         AND YEAR(r.created_at) = ?    AND r.etat_red = 0

         GROUP BY r.brand_id, b.name, DATE(r.created_at)
         ORDER BY jour DESC, total_chiffre_affaires DESC
         LIMIT 5;',
        [$anneeEnCours]
    );

    // Récupérer les données DoD
    $chiffreAffairesDoD = FacadeDB::select(
        'SELECT b.name AS marques, 
                DATE(r.created_at) AS jour, 
                SUM(r.prix_produit) AS total_chiffre_affaires 
         FROM redirections r 
         JOIN marques b ON r.brand_id = b.id 
         WHERE DATE(r.created_at) = CURRENT_DATE() 
         AND YEAR(r.created_at) = ?   AND r.etat_red = 0

         GROUP BY r.brand_id, b.name, DATE(r.created_at)
         ORDER BY total_chiffre_affaires DESC
         LIMIT 5;',
        [$anneeEnCours]
    );

    // Récupérer les données MTD par SKU
    $chiffreAffairesSKUMTD = FacadeDB::select(
        'SELECT p.sku AS sku, SUM(r.prix_produit) AS total_chiffre_affaires
         FROM redirections r
         JOIN produit p ON r.product_id = p.id
         WHERE YEAR(r.created_at) = ? AND MONTH(r.created_at) = MONTH(CURDATE())   AND r.etat_red = 0
  
         GROUP BY p.sku
         ORDER BY total_chiffre_affaires DESC
         LIMIT 10;',
        [$anneeEnCours]
    );

    // Récupérer les données DoD par SKU
    $chiffreAffairesSKUDOD = FacadeDB::select(
        'SELECT p.sku AS sku, SUM(r.prix_produit) AS total_chiffre_affaires
FROM redirections r
JOIN produit p ON r.product_id = p.id
WHERE DATE(r.created_at) = CURDATE() 
  AND YEAR(r.created_at) = ? 
  AND r.etat_red = 0
GROUP BY p.sku
ORDER BY total_chiffre_affaires DESC
LIMIT 10;
',
        [$anneeEnCours]
    );

    // Récupérer les données YTD par SKU
    $chiffreAffairesSKUYTD = FacadeDB::select(
        'SELECT p.sku AS sku, SUM(r.prix_produit) AS total_chiffre_affaires
         FROM redirections r
         JOIN produit p ON r.product_id = p.id
         WHERE YEAR(r.created_at) = ?   AND r.etat_red = 0
         GROUP BY p.sku
         ORDER BY total_chiffre_affaires DESC
         LIMIT 10;',
        [$anneeEnCours]
    );

    // Récupérer les données des revendeurs
    $chiffresAffairesRev = FacadeDB::select(
        'SELECT 
            rev.name AS revendeur_name,
            SUM(CASE 
                    WHEN red.reduction_produit > 0 THEN 
                        (red.prix_produit * (1 - (red.reduction_produit / 100.0)))
                    ELSE 
                        red.prix_produit 
                END) AS chiffre_affaire_YTD,
            SUM(CASE 
                    WHEN red.reduction_produit > 0 AND MONTH(red.created_at) = MONTH(CURDATE()) AND YEAR(red.created_at) = ? THEN 
                        (red.prix_produit * (1 - (red.reduction_produit / 100.0)))
                    ELSE 
                        red.prix_produit 
                END) AS chiffre_affaire_MTD,
            SUM(CASE 
                    WHEN red.reduction_produit > 0 AND DATE(red.created_at) = CURDATE() AND YEAR(red.created_at) = ? THEN 
                        (red.prix_produit * (1 - (red.reduction_produit / 100.0)))
                    ELSE 
                        red.prix_produit 
                END) AS chiffre_affaire_DoD
         FROM 
             redirections AS red
         JOIN 
             revendeurs AS rev ON red.revendeur_id = rev.id
         WHERE 
             YEAR(red.created_at) = ?   AND red.etat_red = 0
         GROUP BY 
             revendeur_name
         ORDER BY 
             chiffre_affaire_YTD DESC
         LIMIT 10;',
        [$anneeEnCours, $anneeEnCours, $anneeEnCours]
    );

    // Récupérer les redirections
$redirection = FacadeDB::select(
    'SELECT 
        r.reference AS ref_redirection,
        GROUP_CONCAT(CONCAT(p.name, " (", r.prix_produit, " TND)") ORDER BY p.name SEPARATOR ", ") AS produits_avec_prix,
        r.client_nom,
        r.client_prenom,
        r.client_email,
        r.client_phone,
        r.client_adresse,
        SUM(r.prix_produit * (1 - r.reduction_produit / 100)) AS total_commande,
        v.name AS revendeur_nom,
        MIN(r.created_at) AS date_premiere_redirection,
        MAX(r.created_at) AS date_derniere_redirection,
        r.etat_red,
        CASE 
            WHEN r.etat_red = 2 THEN "Annulé"
            WHEN r.etat_red = 1 THEN "Envoyé au revendeur"
            WHEN r.etat_red = 0 THEN "Validé, envoyé au client"
            WHEN r.etat_red = 3 THEN "Renvoyé"
            ELSE "Non défini"
        END AS etat_redirection
    FROM 
        redirections AS r
    JOIN 
        produit AS p ON r.product_id = p.id
    JOIN 
        revendeurs AS v ON r.revendeur_id = v.id
    WHERE 
        YEAR(r.created_at) = YEAR(CURDATE()) -- Année en cours
    GROUP BY 
        r.reference,
        r.client_nom,
        r.client_prenom,
        r.client_email,
        r.client_phone,
        r.client_adresse,
        v.name,
        r.etat_red
    ORDER BY 
        r.created_at DESC
    LIMIT 30;'
);


    // Passer les données à la vue
    return view('themes.dashboard', compact(
        'redirection',
        'chiffreAffairesYTD', 
        'chiffresAffairesRev',
        'chiffreAffairesMTD',
        'chiffreAffairesSKUYTD', 
        'chiffreAffairesDoD', 
        'chiffreAffairesSKUMTD', 
        'chiffreAffairesSKUDOD'
    ));
}

     
public function statistiquesMarques()
{
    // Récupérer les données par marque : Nom, Chiffre d'affaires, Nombre de produits
    $chiffreParMarque = DB::select(
        'SELECT 
            b.name AS marque, 
            SUM(r.prix_produit) AS total_chiffre_affaires, 
            COUNT(r.id) AS nombre_produits
         FROM redirections r
         JOIN marques b ON r.brand_id = b.id
         WHERE   r.etat_red = 0
         GROUP BY r.brand_id, b.name
         ORDER BY total_chiffre_affaires DESC'
    );

    // Passer les données à la vue
    return view('themes.marque', compact('chiffreParMarque'));
}
public function statistiquesDetaillees()
{
    $currentYear = date('Y'); // Obtenir l'année en cours

    // Exécuter une requête SQL pour obtenir les données de l'année en cours
    $statistiquesDetaillees = DB::select("
        SELECT 
            MONTH(r.created_at) AS mois,
            DATE(r.created_at) AS date,
            CONCAT(r.client_nom, ' ', r.client_prenom) AS redirections,
            r.nom_produit AS article,
            r.prix_produit AS prix,
            revendeurs.name AS revendeurs,
            'source_placeholder' AS source, 
            m.name AS marques,
            SUM(r.prix_produit) OVER (PARTITION BY MONTH(r.created_at)) AS total_mensuel,
            COUNT(r.id) OVER (PARTITION BY MONTH(r.created_at)) AS nb_articles_vendus_mois,
            AVG(r.prix_produit) OVER (PARTITION BY MONTH(r.created_at)) AS val_moyenne_article_mois,
            SUM(r.prix_produit) OVER () AS total_annuel,
            COUNT(r.id) OVER () AS nb_articles_vendus_annee,
            AVG(r.prix_produit) OVER () AS val_moyenne_article_annee,
            (COUNT(r.id) OVER (PARTITION BY DATE(r.created_at))) / DAYOFYEAR(r.created_at) AS moy_article_jour,
            (COUNT(r.id) OVER (PARTITION BY WEEK(r.created_at))) / 7 AS moy_article_semaine
        FROM 
            redirections r
        JOIN 
            marques m ON r.brand_id = m.id
        JOIN 
            revendeurs ON r.revendeur_id = revendeurs.id
        WHERE 
            YEAR(r.created_at) = :currentYear AND  AND r.etat_red = 0
        ORDER BY 
            r.created_at DESC
    ", ['currentYear' => $currentYear]);

    return view('themes.marque', compact('statistiquesDetaillees'));
}

public function getRedirectionStatisticsBrand()
{
    // Dates de référence
    $today = Carbon::today();
    $firstDayOfYear = Carbon::now()->startOfYear();
    $firstDayOfMonth = Carbon::now()->startOfMonth();
    $last7Days = Carbon::today()->subDays(7);
    $yesterday = Carbon::yesterday();

    // Requête principale pour les statistiques
    $RedirectionStatisticsBrand = DB::table('redirections as r')
        ->join('produit as p', 'r.product_id', '=', 'p.id')
        ->join('revendeurs as v', 'r.revendeur_id', '=', 'v.id')
        ->join('marques as b', 'p.brand_id', '=', 'b.id')
        ->select(
            'b.name as brand_name',
            DB::raw("GROUP_CONCAT(CONCAT(p.name, ' (', r.prix_produit, ' TND)') ORDER BY p.name SEPARATOR ', ') as produits_avec_prix"),
            DB::raw('SUM(r.prix_produit * (1 - r.reduction_produit / 100)) as total_commande'),
            DB::raw('COUNT(r.id) as total_redirections'),
            DB::raw('SUM(CASE WHEN r.created_at >= ? THEN r.prix_produit * (1 - r.reduction_produit / 100) ELSE 0 END) as ytd_revenue'),
            DB::raw('SUM(CASE WHEN r.created_at >= ? THEN r.prix_produit * (1 - r.reduction_produit / 100) ELSE 0 END) as mtd_revenue'),
            DB::raw('SUM(CASE WHEN r.created_at >= ? THEN r.prix_produit * (1 - r.reduction_produit / 100) ELSE 0 END) as l7d_revenue'),
            DB::raw('SUM(CASE WHEN r.created_at >= ? THEN r.prix_produit * (1 - r.reduction_produit / 100) ELSE 0 END) as today_revenue'),
            DB::raw('SUM(CASE WHEN r.created_at >= ? AND r.created_at < ? THEN r.prix_produit * (1 - r.reduction_produit / 100) ELSE 0 END) as yesterday_revenue')
        )
        ->groupBy('b.name')
        ->orderByDesc('ytd_revenue')
        ->setBindings([$firstDayOfYear, $firstDayOfMonth, $last7Days, $today, $yesterday, $today])
        ->get();

    // Calcul des totaux globaux
    $totals = [
        'ytd' => $RedirectionStatisticsBrand->sum('ytd_revenue'),
        'mtd' => $RedirectionStatisticsBrand->sum('mtd_revenue'),
        'l7d' => $RedirectionStatisticsBrand->sum('l7d_revenue'),
        'today' => $RedirectionStatisticsBrand->sum('today_revenue'),
        'yesterday' => $RedirectionStatisticsBrand->sum('yesterday_revenue'),
    ];

    // Calcul des pourcentages de contribution
    foreach ($RedirectionStatisticsBrand as $stat) {
        $stat->ytd_percentage = $totals['ytd'] > 0 ? ($stat->ytd_revenue / $totals['ytd']) * 100 : 0;
        $stat->mtd_percentage = $totals['mtd'] > 0 ? ($stat->mtd_revenue / $totals['mtd']) * 100 : 0;
        $stat->l7d_percentage = $totals['l7d'] > 0 ? ($stat->l7d_revenue / $totals['l7d']) * 100 : 0;
        $stat->today_percentage = $totals['today'] > 0 ? ($stat->today_revenue / $totals['today']) * 100 : 0;
        $stat->yesterday_percentage = $totals['yesterday'] > 0 ? ($stat->yesterday_revenue / $totals['yesterday']) * 100 : 0;
    }

    // Préparer les données pour le graphique
    $chartData = [
        'labels' => $RedirectionStatisticsBrand->pluck('brand_name')->toArray(),
        'datasets' => [
            [
                'label' => 'Revenus Année (YTD)',
                'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                'borderColor' => 'rgba(75, 192, 192, 1)',
                'data' => $RedirectionStatisticsBrand->pluck('ytd_revenue')->toArray(),
            ],
            [
                'label' => 'Revenus Mois (MTD)',
                'backgroundColor' => 'rgba(255, 206, 86, 0.2)',
                'borderColor' => 'rgba(255, 206, 86, 1)',
                'data' => $RedirectionStatisticsBrand->pluck('mtd_revenue')->toArray(),
            ],
        ],
    ];

    // Retour des données à la vue
    return view('themes.marque', compact('RedirectionStatisticsBrand', 'totals', 'chartData'));
}

public function getRealtimeStatistics(Request $request)
{
    // Récupérer les dates de début et de fin à partir de la requête (si présentes)
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date', now()->toDateString()); // Si la date de fin est vide, prendre la date du jour

    // Si la date de début est spécifiée, la formater et l'utiliser dans la requête
    if ($startDate) {
        $startDate = Carbon::parse($startDate)->startOfDay();
    } else {
        // Si pas de date de début, par défaut, récupérer toutes les données
        $startDate = Carbon::parse('2024-01-01')->startOfDay(); // Par exemple, définir une date de début par défaut
    }

    // Formatage de la date de fin
    $endDate = Carbon::parse($endDate)->endOfDay();

    // Récupérer les classements des marques, SKU, revendeurs et produits
    $brandRanking = DB::table('redirections as r')
                        ->join('marques as m', 'r.brand_id', '=', 'm.id')
                        ->select('m.name', DB::raw('SUM(r.prix_produit) as total'))
                        ->whereBetween('r.created_at', [$startDate, $endDate])
                        ->groupBy('m.name')
                        ->orderByDesc('total')
                        ->get();

    $retailerRanking = DB::table('redirections as r')
                          ->join('revendeurs as rev', 'r.revendeur_id', '=', 'rev.id')
                          ->select('rev.name', DB::raw('SUM(r.prix_produit) as total'))
                          ->whereBetween('r.created_at', [$startDate, $endDate])
                          ->groupBy('rev.name')
                          ->orderByDesc('total')
                          ->get();

    $productRanking = DB::table('redirections as r')
                         ->select('r.nom_produit as product', DB::raw('SUM(r.prix_produit) as total'))
                         ->whereBetween('r.created_at', [$startDate, $endDate])
                         ->groupBy('r.nom_produit')
                         ->orderByDesc('total')
                         ->get();

    // Renvoyer les résultats via JSON (si la requête est AJAX)
    if ($request->ajax()) {
        return response()->json([
            'brandRanking' => view('themes.partials.brandRanking', compact('brandRanking'))->render(),
            'productRanking' => view('themes.partials.productRanking', compact('productRanking'))->render(),
        ]);
    }

    // Passer les données à la vue
    return view('themes.dashboarddetail', compact('brandRanking', 'productRanking', 'startDate', 'endDate'));
}
 

public function updateEtat(Request $request, $ref_redirection)
{
    $request->validate([
        'etat_red' => 'required|integer|in:0,1,2',
    ]);

    // Mettre à jour toutes les redirections correspondant à la même référence
    $updatedRows = Redirection::where('reference', $ref_redirection)
                                ->update(['etat_red' => $request->etat_red]);

    // Vérifier si des lignes ont été mises à jour
    if ($updatedRows > 0) {
        // Rediriger avec un message de succès
        return redirect()->route('redirections.index')->with('success', 'L\'état des redirections a été mis à jour avec succès.');
    } else {
        // Si aucune ligne n'est mise à jour (au cas où la référence n'existe pas)
        return redirect()->route('redirections.index')->with('error', 'Aucune redirection trouvée avec cette référence.');
    }
}

/*Mail redirection */
public function showRedirection($id)
{
    // Récupérer la redirection par ID
    $redirection = Redirection::findOrFail($id);

    // Récupérer les produits associés à la redirection
    $productDetails = $redirection->products; // Si la relation existe avec les produits

    // Passer les données à la vue
    return view('redirection_created', compact('redirection', 'productDetails'));
}


public function storeFromWooCommerce(Request $request)
    {
        // Exemple de récupération des commandes WooCommerce (il faudra ajuster selon l'intégration réelle)
        // Supposons que vous avez une méthode qui récupère les commandes WooCommerce via API ou base de données.
        $ordersFromWooCommerce = $this->getOrdersFromWooCommerce();

        // Validation des commandes récupérées
        foreach ($ordersFromWooCommerce as $order) {
            // Vous pouvez ajuster la logique pour vérifier que la commande contient les informations nécessaires
            if (!isset($order['revendeur_id']) || !isset($order['produits'])) {
                continue;  // Ignorer les commandes incomplètes
            }

            // Créer la redirection pour chaque commande
            $redirection = new Redirection();
            $redirection->order_id = $order['order_id'];
            $redirection->revendeur_id = $order['revendeur_id'];
            $redirection->save();

            // Attacher les produits à la redirection
            $redirection->produits()->attach($order['produits']);
        }

        return redirect()->route('orders.index')->with('success', 'Redirections créées avec succès depuis WooCommerce.');
    }

    public function dashboardRedirection(Request $request)
    {
        // Récupérer les paramètres de la saison et de la marque depuis la requête
        $saison = $request->input('saison');
        $marque_id = $request->input('marque_id');
    
        // Requête SQL pour récupérer les redirections
        $query = "
            SELECT 
                CASE
                    WHEN MONTH(r.created_at) IN (12, 1, 2) THEN 'Hiver'
                    WHEN MONTH(r.created_at) IN (3, 4, 5) THEN 'Printemps'
                    WHEN MONTH(r.created_at) IN (6, 7, 8) THEN 'Été'
                    WHEN MONTH(r.created_at) IN (9, 10, 11) THEN 'Automne'
                    ELSE 'Inconnu'
                END AS saison,
                p.name AS produit_nom,
                m.name AS marque_nom,
                COUNT(r.id) AS nombre_redirections,
                FORMAT(SUM(r.prix_produit * (1 - COALESCE(r.reduction_produit, 0) / 100)), 2) AS total_ventes_saison,
                MAX(r.created_at) AS derniere_redirection
            FROM 
                redirections AS r
            JOIN 
                produit AS p ON r.product_id = p.id
            JOIN 
                marques AS m ON p.brand_id = m.id
            WHERE
                r.etat_red IN (0, 1)  -- Inclure uniquement les redirections validées ou envoyées
                AND (
                    CASE
                        WHEN MONTH(r.created_at) IN (12, 1, 2) THEN 'Hiver'
                        WHEN MONTH(r.created_at) IN (3, 4, 5) THEN 'Printemps'
                        WHEN MONTH(r.created_at) IN (6, 7, 8) THEN 'Été'
                        WHEN MONTH(r.created_at) IN (9, 10, 11) THEN 'Automne'
                        ELSE 'Inconnu'
                    END = ?
                )
                AND (m.id = ? OR ? IS NULL)
            GROUP BY 
                saison, p.name, m.name
            ORDER BY 
                saison, nombre_redirections DESC;
        ";
    
        // Exécuter la requête avec les paramètres
        $data = DB::select($query, [$saison, $marque_id, $marque_id]);
    
        // Charger toutes les marques pour le sélecteur
        $marques = Brand::all();
    
        // Vérifier si la requête est en AJAX (pour un chargement dynamique)
        if ($request->ajax()) {
            return response()->json($data);
        }
    
        // Retourner la vue avec les données
        return view('themes.dashboard_redirection', [
            'data' => $data,
            'saison' => $saison,
            'marque_id' => $marque_id,
            'marques' => $marques
        ]);
    }
 /*Redirection Etat */

 
private function getFilteredRedirections($etat)
{
    return DB::table('redirections as r')
        ->join('produit as p', 'r.product_id', '=', 'p.id')
        ->join('revendeurs as v', 'r.revendeur_id', '=', 'v.id')
        ->select(
            'r.reference as ref_redirection',
            DB::raw("GROUP_CONCAT(CONCAT(p.name, ' (', r.prix_produit, ' TND)') ORDER BY p.name SEPARATOR ', ') as produits_avec_prix"),
            'r.client_nom',
            'r.client_prenom',
            'r.client_email',
            'r.client_phone',
            'r.client_adresse',
            DB::raw('SUM(r.prix_produit * (1 - r.reduction_produit / 100)) as total_commande'),
            'v.name as revendeur_nom',
            DB::raw('MIN(r.created_at) as date_premiere_redirection'),
            DB::raw('MAX(r.created_at) as date_derniere_redirection'),
            'r.etat_red',
            DB::raw("
                CASE 
                    WHEN r.etat_red = 0 THEN 'Annulé'
                    WHEN r.etat_red = 1 THEN 'Envoyé au revendeur'
                    WHEN r.etat_red = 2 THEN 'Validé, envoyé au client'
                    WHEN r.etat_red = 3 THEN 'Renvoyé'
                    ELSE 'Non défini'
                END as etat_redirection
            ")
        )
        ->where('r.etat_red', $etat)
        ->groupBy(
            'r.reference',
            'r.client_nom',
            'r.client_prenom',
            'r.client_email',
            'r.client_phone',
            'r.client_adresse',
            'v.name',
            'r.etat_red'
        )
        ->orderBy('r.created_at', 'DESC')
        ->get();
}



 public function enCours()
{
    $revendeurs = Revendeur::all();

    $redirections = $this->getFilteredRedirections(1); // État 'Envoyé au revendeur'

    return view('themes.redirection_encoure', compact('redirections', 'revendeurs'));
}
    
public function annulees()
{
    $revendeurs = Revendeur::all();

    $redirections = $this->getFilteredRedirections(2); // État 'Annulé'

    return view('themes.redirection_annule', compact('redirections', 'revendeurs'));
}

public function validees()
{
    $revendeurs = Revendeur::all();

    $redirections = $this->getFilteredRedirections(0); // État 'Validé, envoyé au client'

    return view('themes.redirection_valider', compact('redirections', 'revendeurs'));
}


public function proposeProduitsParSaison(Request $request)
{
    $saison = $request->input('saison');

    if ($saison) {
        // Définir les mois associés à chaque saison
        $moisSaison = match ($saison) {
            'Hiver' => [12, 1, 2],
            'Printemps' => [3, 4, 5],
            'Été' => [6, 7, 8],
            'Automne' => [9, 10, 11],
            default => [],
        };

        // Requête pour récupérer les suggestions de produits
        $suggestions = DB::table('redirections as r')
            ->join('produit as p', 'r.product_id', '=', 'p.id')
            ->join('marques as m', 'p.brand_id', '=', 'm.id')
            ->select(
                'p.name as produit_nom',
                'm.name as marque_nom',
                DB::raw('COUNT(r.id) as nombre_redirections'),
                DB::raw('FORMAT(SUM(r.prix_produit * (1 - COALESCE(r.reduction_produit, 0) / 100)), 2) as total_ventes'),
                DB::raw('MAX(r.created_at) as derniere_redirection'),
                DB::raw("CASE
                    WHEN COUNT(r.id) > 10 THEN 'Populaire'
                    WHEN COUNT(r.id) BETWEEN 5 AND 10 THEN 'Recommandé'
                    ELSE 'À promouvoir'
                END as suggestion")
            )
            ->whereIn(DB::raw('MONTH(r.created_at)'), $moisSaison)
            ->whereIn('r.etat_red', [0, 1]) // Redirections validées ou envoyées
            ->groupBy('p.name', 'm.name')
            ->orderBy('nombre_redirections', 'desc')
            ->get()
            ->toArray();
    } else {
        $suggestions = [];
    }
    return view('themes.produitsproposer', [
        'saison' => $saison,
        'suggestions' => $suggestions,
    ]);
}

/*
public function storeWithRevendeur(Request $request)
{
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
        'revendeur_id' => 'required|integer',
        'date_naissance_client' => 'required|date',
        'sexe' => 'required|string|max:255',
        'etat_red' => 'nullable|integer',
        'source_red' => 'required|string',
        'evenements_id' => 'required|string|max:255',
    ]);

    $data = $request->all();
    $data['etat_red'] = $data['etat_red'] ?? 1;
    $data['reference'] = $data['reference'] ?? 'REF' . now()->timestamp . mt_rand(100, 999);

    $commonData = [
        'brand_id' => $data['brand_id'],
        'client_nom' => $data['client_nom'],
        'client_prenom' => $data['client_prenom'],
        'client_email' => $data['client_email'],
        'client_phone' => $data['client_phone'],
        'client_adresse' => $data['client_adresse'],
        'revendeur_id' => $data['revendeur_id'],
        'date_naissance_client' => $data['date_naissance_client'],
        'sexe' => $data['sexe'],
        'etat_red' => $data['etat_red'],
        'source_red' => $data['source_red'],
        'reference' => $data['reference'],
        'evenements_id' => $data['evenements_id'],
    ];

    DB::transaction(function () use ($data, $commonData) {
        foreach ($data['product_id'] as $index => $productId) {
            $redirection = Redirection::create(array_merge($commonData, [
                'product_id' => $productId,
                'nom_produit' => $data['nom_produit'][$index],
                'prix_produit' => $data['prix_produit'][$index],
                'reduction_produit' => $data['reduction_produit'][$index],
            ]));

            try {
                $this->sendEmails($redirection);
            } catch (\Exception $e) {
                Log::error("Erreur lors de l'envoi d'e-mail pour la redirection ID {$redirection->id}: " . $e->getMessage());
            }
        }
    });

    return redirect()->route('themes.ajouterredirection')->with('success', 'Redirection avec revendeur créée avec succès.');
}

// Méthode pour créer une redirection sans revendeur (ou un cas différent)
/*
public function storeWithoutRevendeur(Request $request)
{
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
        'date_naissance_client' => 'required|date',
        'sexe' => 'required|string|max:255',
        'etat_red' => 'nullable|integer',
        'source_red' => 'required|string',
        'evenements_id' => 'required|string|max:255',
    ]);

    $data = $request->all();
    $data['etat_red'] = $data['etat_red'] ?? 1;
    $data['reference'] = $data['reference'] ?? 'REF' . now()->timestamp . mt_rand(100, 999);

    $commonData = [
        'brand_id' => $data['brand_id'],
        'client_nom' => $data['client_nom'],
        'client_prenom' => $data['client_prenom'],
        'client_email' => $data['client_email'],
        'client_phone' => $data['client_phone'],
        'client_adresse' => $data['client_adresse'],
        'date_naissance_client' => $data['date_naissance_client'],
        'sexe' => $data['sexe'],
        'etat_red' => $data['etat_red'],
        'source_red' => $data['source_red'],
        'reference' => $data['reference'],
        'evenements_id' => $data['evenements_id'],
    ];

    DB::transaction(function () use ($data, $commonData) {
        foreach ($data['product_id'] as $index => $productId) {
            $redirection = Redirection::create(array_merge($commonData, [
                'product_id' => $productId,
                'nom_produit' => $data['nom_produit'][$index],
                'prix_produit' => $data['prix_produit'][$index],
                'reduction_produit' => $data['reduction_produit'][$index],
            ]));

            try {
                $this->sendEmails($redirection);
            } catch (\Exception $e) {
                Log::error("Erreur lors de l'envoi d'e-mail pour la redirection ID {$redirection->id}: " . $e->getMessage());
            }
        }
    });

    return redirect()->route('themes.ajouterredirection')->with('success', 'Redirection sans revendeur créée avec succès.');
}
*/

public function getStateRedirection()
{
    $currentYear = date('Y'); // Année en cours

    // Statistiques globales pour l'année en cours
    $totalOrders = Redirection::whereYear('created_at', $currentYear)->distinct('reference')
    ->count('reference');
    $totalRedirections = Redirection::whereYear('created_at', $currentYear)
    ->where('etat_red', 0) // Ajouter la condition pour `etat_red` égal à 2
    ->sum('prix_produit'); 

$revenueYTD = $totalRedirections; // Revenus annuels (YTD) sont les mêmes que le total pour l'année en cours

// Revenus du mois en cours (MTD)
$revenueMTD = Redirection::whereYear('created_at', $currentYear)
    ->whereMonth('created_at', date('m'))
    ->where('etat_red', 0) // Ajouter la condition pour `etat_red` égal à 2
    ->sum('prix_produit');

// Revenus des 7 derniers jours
$revenueLast7Days = Redirection::whereYear('created_at', $currentYear)
    ->where('created_at', '>=', now()->subDays(7))
    ->where('etat_red', 0) // Ajouter la condition pour `etat_red` égal à 2
    ->sum('prix_produit');

// Revenus d'aujourd'hui
$revenueToday = Redirection::whereYear('created_at', $currentYear)
    ->whereDate('created_at', today())
    ->where('etat_red', 0) // Ajouter la condition pour `etat_red` égal à 2
    ->sum('prix_produit');

// Données pour le graphique (Revenus par mois pour l'année en cours)
$revenueData = Redirection::select(
        DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), // Extraire l'année et le mois
        DB::raw('SUM(prix_produit) as total') // Somme des prix des produits
    )
    ->whereYear('created_at', $currentYear) // Filtrer par année en cours
    ->where('etat_red', 0) // Ajouter la condition pour `etat_red` égal à 2
    ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")')) // Regrouper par année et mois
    ->orderBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), 'asc') // Trier par mois
    ->get();


    // Préparation des données pour la vue
    return view('themes.statut_redirection', [
        'totalOrders' => $totalOrders,
        'totalRedirections' => $totalRedirections,
        'revenueYTD' => $revenueYTD,
        'revenueMTD' => $revenueMTD,
        'revenueLast7Days' => $revenueLast7Days,
        'revenueToday' => $revenueToday,
        'revenueData' => $revenueData,
    ]);
}
public function changeRevendeur(Request $request)
{
    // Validation des données de la requête
    $request->validate([
        'reference' => 'required|string',
        'new_revendeur_id' => 'required|string|max:255',
    ]);

    // Récupération des données de la requête
    $reference = $request->input('reference');
    $newRevendeurId = $request->input('new_revendeur_id');

    // Rechercher toutes les redirections avec la même référence
    $redirections = Redirection::where('reference', $reference)->get();

    // Si aucune redirection n'est trouvée
    if ($redirections->isEmpty()) {
        return redirect()->back()->with('error', 'Aucune redirection trouvée avec cette référence.');
    }

    // Mettre à jour le revendeur_id et l'état pour chaque redirection
    foreach ($redirections as $redirection) {
        // Mise à jour de l'état
        $redirection->etat_red = 3; // Mise à jour de l'état à 2

        // Mise à jour du revendeur_id
        $redirection->revendeur_id = $newRevendeurId;

        // Sauvegarder les changements
        $redirection->save();

        // Récupérer le revendeur à partir de l'ID mis à jour
        $revendeur = Revendeur::find($newRevendeurId);

        // Si le revendeur existe, envoyer un e-mail
        if ($revendeur) {
            try {
                $this->sendEmails($redirection); // Appeler la méthode d'envoi d'e-mails
            } catch (\Exception $e) {
                // Si l'envoi d'e-mail échoue, loguer l'erreur et continuer
                Log::error("Erreur lors de l'envoi d'e-mail pour la redirection ID {$redirection->id}: " . $e->getMessage());
            }
        } else {
            // Si le revendeur n'existe pas, loguer un message d'erreur
            Log::error("Revendeur non trouvé pour l'ID {$newRevendeurId} lors de la mise à jour de la redirection ID {$redirection->id}.");
        }
    }

    // Retourner une réponse avec un message de succès
    return redirect()->back()->with('success', 'Le revendeur a été changé pour toutes les redirections de cette référence.');
}



 
public function showMap()
{
    // Récupération des données depuis la base de données
    $redirections = DB::table('redirections')
        ->select('client_adresse', DB::raw('COUNT(DISTINCT reference) AS nombre_clients'))
        ->groupBy('client_adresse')
        ->orderByDesc('nombre_clients')
        ->get();

    // Vérifier si des données existent
    if ($redirections->isEmpty()) {
        return view('themes.cartechaleur', ['redirections' => [], 'message' => 'Aucune donnée disponible.']);
    }

    // Passer les résultats à la vue
    return view('themes.cartechaleur', ['redirections' => $redirections]);
}

public function updateRedirection(Request $request, $reference)
{
    // Validation des données de la requête
    $request->validate([
        'brand_id' => 'required|integer',
        'product_id' => 'required|array|min:1|max:100',
        'nom_produit' => 'required|array|min:1|max:100',
        'qts_produit' => 'required|array|min:1|max:100',
        'prix_intial' => 'required|array|min:1|max:100',
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
        'id_team' => 'required|exists:team,id',
    ]);

    // Récupération des données validées
    $data = $request->all();

    // Récupérer les redirections liées à cette référence
    $redirections = Redirection::where('reference', $reference)->get();

    if ($redirections->isEmpty()) {
        return redirect()->back()->with('error', 'Aucune redirection trouvée pour cette référence.');
    }

    // Démarrer une transaction pour mettre à jour les données
    DB::transaction(function () use ($redirections, $data) {
        // Mise à jour des données communes
        foreach ($redirections as $index => $redirection) {
            $redirection->update([
                'brand_id' => $data['brand_id'],
                'client_nom' => $data['client_nom'],
                'client_prenom' => $data['client_prenom'],
                'client_email' => $data['client_email'],
                'client_phone' => $data['client_phone'],
                'client_adresse' => $data['client_adresse'],
                'revendeur_id' => $data['revendeur_id'],
                'date_naissance_client' => $data['date_naissance_client'],
                'sexe' => $data['sexe'],
                'etat_red' => $data['etat_red'] ?? 1,
                'source_red' => $data['source_red'],
                'evenements_id' => $data['evenements_id'],
                'id_team' => $data['id_team'],
                'product_id' => $data['product_id'][$index],
                'nom_produit' => $data['nom_produit'][$index],
                'qts_produit' => $data['qts_produit'][$index],
                'prix_intial' => $data['prix_intial'][$index],
                'prix_produit' => $data['prix_produit'][$index],
                'reduction_produit' => $data['reduction_produit'][$index],
            ]);
        }
    });

    // Redirection avec message de succès
    return redirect()->route('themes.listeredirection')->with('success', 'Redirection mise à jour avec succès.');
}

/*
public function envoyerRecapitulatifJour()
{
    // Récupérer les résultats des redirections pour aujourd'hui
    $currentDate = date('Y-m-d');
    $resultats = DB::table('redirections')
        ->join('revendeurs', 'redirections.revendeur_id', '=', 'revendeurs.id')
        ->join('marques', 'redirections.brand_id', '=', 'marques.id')
        ->select(
            'revendeurs.name as revendeur',
            'marques.name as marque',
            DB::raw("CONCAT(redirections.client_nom, ' ', redirections.client_prenom) as client"),
            'redirections.client_phone as telephone',
            'redirections.nom_produit as commande',
            'redirections.reference as ref',
            DB::raw('SUM(redirections.prix_produit) as chiffre_affaire')
        )
        ->whereDate('redirections.created_at', $currentDate)  // Filtrer les redirections du jour
        ->groupBy('revendeurs.name', 'marques.name', 'client', 'telephone', 'commande', 'ref')
        ->orderBy('revendeurs.name')
        ->get();

    // Envoyer l'email avec les résultats
    Mail::to('admin@votreentreprise.com')->send(new RappelRedirectionMail($resultats));
}
*/

public function dashboardMarqueRoi()
{
    $data = DB::table('campaigns as c')
    ->leftJoin('evenements as e', 'c.evenement_id', '=', 'e.id')
    ->leftJoin('marques as m', 'e.brand_id', '=', 'm.id')
    ->leftJoin('redirections as r', 'c.evenement_id', '=', 'r.evenements_id')
    ->select(
        'c.nom as campaign_name',
        'm.name as marque_name',
        'c.budget as total_budget',
        DB::raw('SUM(r.prix_produit) as total_revenue'),
        DB::raw('((SUM(r.prix_produit) - c.budget) / c.budget) * 100 as roi_percentage')
    )
    ->groupBy('c.id', 'c.nom', 'm.name', 'c.budget')
    ->get();


    return view('themes.dashboard_marque', compact('data'));
} 




/*


public function showReport(Request $request)
{
    // Récupérer la liste des marques
    $marques = Brand::all();

    // Récupérer le classement des revendeurs
    $revendeursRanking = DB::table('redirections AS r')
        ->join('revendeurs AS rev', 'r.revendeur_id', '=', 'rev.id')
        ->join('produit AS p', 'r.product_id', '=', 'p.id')
        ->when($request->has('marque') && $request->marque != '', function ($query) use ($request) {
            return $query->where('p.brand_id', $request->marque);  // Filtrage par marque
        })
        ->when($request->has('year') && $request->year != '', function ($query) use ($request) {
            return $query->whereYear('r.created_at', $request->year);  // Filtrage par année
        })
        ->select(
            'rev.name AS revendeur',
            DB::raw('SUM(r.prix_produit * (1 - COALESCE(r.reduction_produit, 0) / 100)) AS ca_revendeur')
        )
        ->groupBy('rev.name')
        ->orderByDesc('ca_revendeur')
       
        ->get();

    // Récupérer le classement des SKU avec le nombre de produits vendus
    $skuRanking = DB::table('redirections AS r')
        ->join('produit AS p', 'r.product_id', '=', 'p.id')
        ->when($request->has('marque') && $request->marque != '', function ($query) use ($request) {
            return $query->where('p.brand_id', $request->marque);  // Filtrage par marque
        })
        ->when($request->has('year') && $request->year != '', function ($query) use ($request) {
            return $query->whereYear('r.created_at', $request->year);  // Filtrage par année
        })
        ->select(
            'p.sku AS produit_sku',
            DB::raw('SUM(r.prix_produit * (1 - COALESCE(r.reduction_produit, 0) / 100)) AS ca_sku'),
            DB::raw('COUNT(r.id) AS nbr_ventes')  // Nombre de ventes pour chaque SKU
        )
        ->groupBy('p.sku')
        ->orderByDesc('ca_sku')
        ->limit(10)
        ->get();

    // Compter le nombre total de SKU
    $totalSKUs = DB::table('produit')->count();

    // Filtrer les redirections par marque et année si sélectionnées
    $query = DB::table('redirections AS r')
        ->join('marques AS m', 'r.brand_id', '=', 'm.id')
        ->join('revendeurs AS rev', 'r.revendeur_id', '=', 'rev.id')
        ->join('produit AS p', 'r.product_id', '=', 'p.id')
        ->select(
            'm.name AS marque',
            DB::raw('SUM(r.prix_produit * (1 - COALESCE(r.reduction_produit, 0) / 100)) AS total_ca_marque'),
            'rev.name AS revendeur',
            DB::raw('COUNT(r.id) AS total_redirections_revendeur'),
            'p.sku AS produit_sku',
            'p.name AS produit_nom',
            DB::raw('SUM(r.prix_produit * (1 - COALESCE(r.reduction_produit, 0) / 100)) AS total_ca_produit'),
            DB::raw('COUNT(r.id) AS total_redirections_produit'),
            DB::raw('SUM(CASE WHEN r.revendeur_id = rev.id THEN r.prix_produit * (1 - COALESCE(r.reduction_produit, 0) / 100) END) AS ca_revendeur_par_marque')
        )
        ->groupBy('m.name', 'rev.name', 'p.sku', 'p.name')
        ->orderByDesc('total_ca_marque')
        ->orderByDesc('total_ca_produit')
        ->orderByDesc('total_redirections_produit');

    // Appliquer le filtre par marque si une marque est sélectionnée
    if ($request->has('marque') && $request->marque != '') {
        $query->where('r.brand_id', $request->marque);
    }

    // Appliquer le filtre par année si une année est sélectionnée
    if ($request->has('year') && $request->year != '') {
        $query->whereYear('r.created_at', $request->year);
    }

    // Récupérer les données du rapport
    $reportData = $query->get();

    // Passer les données à la vue
    return view('themes.report', compact('reportData', 'marques', 'revendeursRanking', 'skuRanking', 'totalSKUs'));
}

*/

  
public function showReport(Request $request)
{
    // Récupérer la liste des marques
    $marques = Brand::all();

    // Déterminer l'année sélectionnée (par défaut : année en cours)
    $annee = $request->has('annee') && !empty($request->annee) ? $request->annee : date('Y');

    // Récupérer le classement des revendeurs pour l'année sélectionnée
    $revendeursRanking = DB::table('redirections AS r')
        ->join('revendeurs AS rev', 'r.revendeur_id', '=', 'rev.id')
        ->join('produit AS p', 'r.product_id', '=', 'p.id')
        ->when($request->has('marque') && !empty($request->marque), function ($query) use ($request) {
            return $query->where('p.brand_id', $request->marque);  // Filtrage par marque
        })
        ->whereYear('r.created_at', $annee)  // Filtrer en fonction de l'année sélectionnée
        ->select(
            'rev.name AS revendeur',
            DB::raw('SUM(r.prix_produit * (1 - COALESCE(r.reduction_produit, 0) / 100)) AS ca_revendeur')
        )
        ->groupBy('rev.name')
        ->orderByDesc('ca_revendeur')
        ->get();

    // Récupérer le classement des SKU pour l'année sélectionnée
    $skuRanking = DB::table('redirections AS r')
        ->join('produit AS p', 'r.product_id', '=', 'p.id')
        ->when($request->has('marque') && !empty($request->marque), function ($query) use ($request) {
            return $query->where('p.brand_id', $request->marque);  // Filtrage par marque
        })
        ->whereYear('r.created_at', $annee)  // Filtrer en fonction de l'année sélectionnée
        ->select(
            'p.sku AS produit_sku',
            DB::raw('SUM(r.prix_produit * (1 - COALESCE(r.reduction_produit, 0) / 100)) AS ca_sku'),
            DB::raw('COUNT(r.id) AS nbr_ventes')  // Nombre de ventes pour chaque SKU
        )
        ->groupBy('p.sku')
        ->orderByDesc('ca_sku')
        ->limit(10)
        ->get();

    // Filtrer les redirections par marque et année sélectionnée
    $query = DB::table('redirections AS r')
        ->join('marques AS m', 'r.brand_id', '=', 'm.id')
        ->join('revendeurs AS rev', 'r.revendeur_id', '=', 'rev.id')
        ->join('produit AS p', 'r.product_id', '=', 'p.id')
        ->select(
            'rev.name AS revendeur',
            DB::raw('SUM(r.prix_produit * (1 - COALESCE(r.reduction_produit, 0) / 100)) AS total_ca_revendeur'),
            DB::raw('COUNT(r.id) AS total_redirections_revendeur'),
            'm.name AS marque',
            DB::raw('SUM(r.prix_produit * (1 - COALESCE(r.reduction_produit, 0) / 100)) AS total_ca_marque')
        )
        ->whereYear('r.created_at', $annee)  // Filtrer uniquement pour l'année sélectionnée
        ->groupBy('rev.name', 'm.name')
        ->orderByDesc('total_ca_revendeur')
        ->orderByDesc('total_ca_marque');

    // Appliquer le filtre par marque si une marque est sélectionnée
    if ($request->has('marque') && !empty($request->marque)) {
        $query->where('r.brand_id', $request->marque);
    }

    // Récupérer les données du rapport
    $reportData = $query->get();
    $totalCaRevendeurs = $revendeursRanking->sum('ca_revendeur');

    // Passer les données à la vue
    return view('themes.report', compact('reportData', 'marques', 'revendeursRanking', 'skuRanking', 'totalCaRevendeurs', 'annee'));
}






}
 



 