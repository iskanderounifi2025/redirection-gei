<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CommercialController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\RedirectionsController;
use App\Http\Controllers\RevendeursController;
use App\Http\Controllers\EquipeController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DirectionsController;
use App\Http\Controllers\EvenementsController;
use App\Http\Controllers\CampaignsController ;
use App\Http\Controllers\MailController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\WooCommerceController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\MailboxController;
use App\Http\Controllers\TvDashbaordController;
use App\Http\Controllers\SMTPConfigController;
use App\Http\Controllers\MailSettingController;



 Route::get('revendeurs/{id}/toggle-status', [RevendeursController::class, 'toggleStatus'])->name('revendeurs.toggleStatus');


use Carbon\Carbon;

// Routes de vue statiques
Route::get('/', function () {
    return view('themes.login');
});
Route::get('config', function () {
    return view('themes.smtp.config');
});
Route::get('inbox', function () {
    return view('themes.smtp.inbox');
});

Route::get('dashboard_admin', function () {
    return view('themes.admin.dashboard');
});
/*
Route::get('product', function () {
    return view('themes.orders.index');
});*/

Route::get('sites', function () {
    return view('themes.sites.index');
});
 

Route::get('dashboarddetail', function () {
    return view('themes.dashboarddetail');
});
Route::get('/dashboard', function () {
    return view('themes.dashboard');
});

Route::get('/dbconn', function () {
    return view('dbconn');
});
Route::get('/ajouterevenement', function () {
    return view('themes.ajouterevenement');
});
Route::get('/ajouterredirection', function () {
    return view('themes.ajouterredirection');
});
// Routes pour afficher les pages d'ajout
Route::get('/ajoutermarque', function () {
    return view('themes.ajoutermarque');
});

 // Routes pour afficher les pages d'ajout
Route::get('/ajouterrevendeur', function () {
    return view('themes.ajouterrevendeur');
});
Route::get('/login', function () {
    return view('themes.login');
});
Route::get('/password-oublier', function () {
    return view('themes.password-oublier');
});

Route::get('/listerevendeur', function () {
    return view('themes.listerevendeur');
});
Route::get('/ajouterproduit', function () {
    return view('themes.ajouterproduit');
});
Route::get('/listeproduit', function () {
    return view('themes.listeproduit');
});

Route::get('/listeequipe', function () {
    return view('themes.listeequipe');
});
Route::get('/listeredirection', function () {
    return view('themes.listeredirection');
});
Route::get('/cartechaleur', function () {
    return view('themes.cartechaleur');
});

Route::get('/ajouterequipe', function () {
    return view('themes.ajouterequipe');
});

Route::get('/redirection_created', function () {
    return view('themes.redirection_created');
});
Route::get('/ajoutercategorie', function () {
    return view('themes.ajoutercategorie');
});
Route::get('/listerevendeur', function () {
    return view('themes.listerevendeur');
});
 Route::get('/chart_produit', function () {
    return view('themes.chart_produit');
});
Route::get('/marque', function () {
    return view('themes.marque');
});
Route::get('/ajoutercompagne', function () {
    return view('themes.ajoutercompagne');
}); 

Route::get('/dashboard_revendeur', function () {
    return view('themes.dashboard_revendeur');
}); 
// Routes de vue statiques
Route::get('mail', function () {
    return view('themes.emails.index');
});
// Routes de vue statiques
Route::get('mailbox_config', function () {
    return view('themes.emails.mailbox_config');
});
// Routes de vue statiques
Route::get('parameter', function () {
    return view('themes.profile.settings');
});
 
// Routes de vue statiques
Route::get('listecompagne', function () {
    return view('themes.listecompagne');
});
// Routes de vue statiques
Route::get('listecompagne', function () {
    return view('themes.listecompagne');
});

// Routes de vue statiques
Route::get('encours', function () {
    return view('themes.redirection_encoure');
});

Route::get('valider', function () {
    return view('themes.redirection_valider');
});

Route::get('annule', function () {
    return view('themes.redirection_annule');
});
Route::get('modifier', function () {
    return view('themes.profile.edit');
});
Route::get('dashboard_tv', function () {
    return view('themes.tv.dashboard');
});

Route::get('listeevenement', function () {
    return view('themes.listeevenement');
});
Route::get('statut_redirection', function () {
    return view('themes.statut_redirection');
});
Route::get('produitsproposer', function () {
    return view('themes.produitsproposer');
});
Route::get('report', function () {
    return view('themes.report');
});

Route::get('emails-redirections', function () {
    return view('themes.emails-redirections');
});
Route::get('/ajoutercommercial', [CommercialController::class, 'create'])->name('commercials.create');

// Routes de ressources pour les contrôleurs principaux
Route::resource('brands', BrandController::class);
Route::resource('commercials', CommercialController::class);
Route::resource('produits', ProduitController::class);

// Route pour stocker un commercial
Route::resource('commercials', CommercialController::class);

// Route pour stocker un produit
Route::post('/produit', [ProduitController::class, 'store'])->name('produit.store');

// Route pour afficher la liste des marques
Route::get('/listmarque', function () {
    return view('themes.listemarque');
});
Route::get('/dashboard_marque', function () {
    return view('themes.dashboard_marque');
});
// Route pour afficher la liste des marques
Route::get('/dashboard_evenement', function () {
    return view('themes.dashboard_evenement');
});
Route::get('/redirection_created', function () {
    return view('themes.redirection_created');
});


Route::get('/dashboard_redirection', function () {
    return view('themes.dashboard_redirection');
});


Route::get('/dashboard_produit', function () {
    return view('themes.dashboard_produit');
});
// Route pour afficher la liste des produits (index)
//Route::get('/listeproduit', [ProduitController::class, 'index'])->name('produits.index');
// Route pour afficher la liste des produits (index)
 // Route pour afficher la page d'ajout de produit
//Route::get('/ajouterproduit', [BrandController::class, 'index'])->name('produit.create'); // Assurez-vous d'utiliser le bon contrôleur ici.
Route::get('/listemarque', [ProduitController::class, 'index'])->name('liste.create'); // Assurez-vous d'utiliser le bon contrôleur ici.
Route::get('/ajoutermarque', [CommercialController::class, 'indexForAddCommercial'])->name('commercials.index');
Route::get('/ajouterproduit', [BrandController::class, 'index'])->name('e');
Route::get('/listemarque', [BrandController::class, 'indexForBrand'])->name('brands.index');
Route::get('/ajouterevenement', [BrandController::class, 'indexForEvenement'])->name('brands.index');
Route::get('/listeevenement', [BrandController::class, 'indexForEvenements'])->name('brands.index');

Route::get('/dashboard_evenement', [BrandController::class, 'indexForDashaboardEvenements'])->name('brands.index');

Route::get('/api/search-sku', [ProduitController::class, 'searchSku']);
Route::resource('redirections', RedirectionsController::class);
Route::get('/ajouterredirection', [ProduitController::class, 'indexForProduitRedirection'])->name('produits.index');
Route::get('/ajouterredirection', [RedirectionsController::class, 'createRedirection'])->name('themes.ajouterredirection');
Route::get('themes.ajouterredirection', [ProduitController::class, 'getProductsByBrand']);
Route::get('/get-products-by-brand', [ProduitController::class, 'getProductsByBrand'])->name('getProductsByBrand');


 
Route::get('/listerevendeur', [RevendeursController::class, 'index'])->name('revendeurs.index');
Route::post('/ajouterrevendeur', [RevendeursController::class, 'store'])->name('revendeurs.store');
Route::resource('revendeurs', RevendeursController::class);
 
/**/
Route::get('/listeredirection', [RedirectionsController::class, 'index'])->name('redirections.index');

Route::resource('/listeredirection', RedirectionsController::class);
Route::get('/cartechaleur', [RedirectionsController::class, 'showMap'])->name('redirections.index');

// Dans routes/web.php
Route::get('redirections/reference/{reference}', [RedirectionsController::class, 'showByReference'])->name('redirections.byReference');

Route::resource('Equipes', EquipeController::class);
Route::post('/ajouterequipe', [EquipeController::class, 'store'])->name('equipe.store');

Route::get('/ajouterequipe', [BrandController::class, 'indexForteam'])->name('brands.index');
Route::get('/listeequipe', [EquipeController::class, 'index'])->name('equipes.index');
Route::get('/equipes/{id}/edit', [EquipeController::class, 'edit'])->name('equipes.edit');
Route::put('/equipes/{id}', [EquipeController::class, 'update'])->name('equipes.update');
Route::delete('/equipes/{id}', [EquipeController::class, 'destroy'])->name('equipes.destroy');
Route::post('/equipes/{id}/toggle-status', [EquipeController::class, 'toggleStatus'])->name('equipes.toggleStatus');

//categorie
Route::resource('ajoutercategorie', CategoriesController::class);
Route::get('/themes/ajoutercategorie', [CategoriesController::class, 'index'])->name('themes.ajoutercategorie');

Route::post('/ajoutercategorie', [CategoriesController::class, 'store'])->name('Categorie.store');
Route::put('/categories/{id}', [CategoriesController::class, 'update'])->name('categorie.update');

//Direction themes.ajoutercategorie

Route::resource('directions', DirectionsController::class);
Route::post('/ajouterdirection', [DirectionsController::class, 'store'])->name('Directions.store');
Route::get('/themes/ajouterdirection', [DirectionsController::class, 'index'])->name('themes.ajouterdirection');
Route::get('/ajouterdirection', [DirectionsController::class, 'index'])->name('Directions.index');
Route::put('/directions/{id}', [DirectionsController::class, 'update'])->name('directions.update');

//Redirection

Route::get('/redirections/create', [RedirectionsController::class, 'create'])->name('redirections.create');
Route::post('/ajouterredirection', [RedirectionsController::class, 'store'])->name('redirections.store');
Route::get('/redirections', [RedirectionsController::class, 'index'])->name('redirections.index');
Route::get('/redirections/{id}', [RedirectionsController::class, 'show'])->name('redirections.show');
Route::get('/redirections/{id}/edit', [RedirectionsController::class, 'edit'])->name('redirections.edit');
Route::put('/redirections/{id}', [RedirectionsController::class, 'update'])->name('redirections.update');
Route::delete('/redirections/{id}', [RedirectionsController::class, 'destroy'])->name('redirections.destroy');
Route::get('/dashboard', [RedirectionsController::class, 'statistiquesMarquesDashboard'])->name('dashboard');
Route::get('/ajouterrevendeur', [DirectionsController::class, 'indexForAddRevendeur'])->name('directions.index');
Route::get('/marque', [RedirectionsController::class, 'statistiquesDetaillees'])->name('marque');

//Route::get('/dashboard', [RedirectionsController::class, 'chiffreAffairesStatistiques'])->name('themes.dashboard');

// Route pour accéder aux statistiques des marques
 
Route::get('/ajouterredirection', [BrandController::class, 'indexForRedirection'])->name('brands.index');


//Route::get('/ajouterredirection', [RedirectionsController::class, 'getRevendeurs'])->name('revendeurs.index');
Route::get('/ajouterredirection', [RedirectionsController::class, 'create'])->name('brands.create');
Route::get('/ajouterredirection', [RedirectionsController::class, 'create'])->name('redirections.create');
Route::get('/listeproduit', [ProduitController::class, 'index']);
Route::get('/brand/{brandId}/products', [ProduitController::class, 'getProductsByBrand']);
//Route::resource('ajouterevenement', EvenementsController::class);
 Route::get('/listeevenement', [EvenementsController::class, 'index'])->name('themes.listeevenement');
 Route::resource('evenements', EvenementsController::class);  // Cette ligne génère toutes les routes nécessaires pour CRUD
 Route::put('/evenements/{id}', [EvenementsController::class, 'update']);
 //Route::get('/evenements/{id}/edit', [EventController::class, 'edit']);
 Route::delete('/listeevenement', [EvenementsController::class, 'destroy']);
 Route::put('/listeequipe/{id}', [EquipeController::class, 'update'])->name('equipes.update');
 Route::put('equipes/{id}/toggle-status', [EquipeController::class, 'toggleStatus'])->name('equipes.toggleStatus');
 Route::put('listeequipe/{id}', [EquipeController::class, 'update'])->name('themes.listeequipe');
 //Route::get('/listeredirection', [RedirectionsController::class, 'indexRedirectionsGrouped'])->name('redirections.indexGrouped');
 //Route::get('/redirection/{ref_redirection}/edit', [RedirectionController::class, 'edit'])->name('redirection.edit');
 Route::delete('/redirection/{ref_redirection}', [RedirectionsController::class, 'destroy'])->name('redirection.destroy');
 Route::post('/redirection/update', [RedirectionsController::class, 'update'])->name('redirection.update');
 Route::get('/produits/{produit}/edit', [ProduitController::class, 'edit'])->name('produits.edit');
 Route::put('/produits/{produit}', [ProduitController::class, 'update'])->name('produits.update');
 Route::put('/brands/{id}', [BrandController::class, 'update'])->name('brands.update');
 //Login Form 
Route::get('/login', [EquipeController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [EquipeController::class, 'login'])->name('login');
//Logout 
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login'); // Redirige vers la page de connexion après la déconnexion
})->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [RedirectionsController::class, 'statistiquesMarquesDashboard'])->name('dashboard');
});

//
Route::get('/ajouterredirection', [RedirectionsController::class, 'create'])->name('themes.ajouterredirection');
Route::get('/ajouterrevendeur', [RevendeursController::class, 'create'])->name('themes.ajouterrevendeur');
Route::put('/revendeurs/{id}', [RevendeursController::class, 'update'])->name('revendeurs.update');
 
/*marque*/
Route::get('/marque', [RedirectionsController::class, 'getRedirectionStatisticsBrand'])
    ->name('marque');// Route pour afficher le formulaire de création
    Route::get('/ajoutercompagne', [CampaignsController::class, 'create'])->name('themes.ajoutercompagne');
    Route::get('/listecompagne', [CampaignsController::class, 'index'])->name('campaigns.liste');
    // Route pour enregistrer une nouvelle campagne
    Route::post('/ajoutercompagne', [CampaignsController::class, 'store'])->name('campaign.store');
    
    // Route pour afficher la liste des campagnes
    Route::get('/ajoutercompagne', [CampaignsController::class, 'index'])->name('themes.ajoutercompagne');
    
    // Route pour éditer une campagne existante
    Route::get('/edit-campagne/{id}', [CampaignsController::class, 'edit'])->name('themes.editcampagne');
    
    // Route pour supprimer une campagne
    Route::delete('/delete-campagne/{id}', [CampaignsController::class, 'destroy'])->name('themes.deletecampagne');

Route::get('/dashboarddetail', [RedirectionsController::class, 'getRealtimeStatistics'])->name('themes.dashboarddetail');
 
/*Mail*/ 

Route::get('/emails', [MailController::class, 'index'])->name('emails.index');
Route::get('/emails/read/{id}', [MailController::class, 'read'])->name('emails.read');
Route::post('/save-settings', [SettingsController::class, 'saveSettings'])->name('saveSettings');
Route::post('/save-general-settings', [SettingsController::class, 'saveGeneralSettings'])->name('saveGeneralSettings');

/*Rediction mdifier*/ 
// Route pour mettre à jour le revendeur
 Route::patch('/redirections/{ref_redirection}/update-etat', [RedirectionsController::class, 'updateEtat'])->name('redirections.updateEtat');
// Route pour gérer les sites (resource route)
Route::resource('sites', SiteController::class); // Gestion complète des sites
Route::resource('orders', WooCommerceController::class); // Gestion des commandes

Route::get('site/{siteId}/orders', [SiteController::class, 'showOrders'])->name('site.orders');
 Route::get('woocommerce/{id}', [WooCommerceController::class, 'show'])->name('sites.orders');
//dashbard admin
Route::get('/dashboard_admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
 //Dashbaord Tv

Route::get('sites', [SiteController::class, 'index'])->name('sites.index');
Route::get('sites/create', [SiteController::class, 'create'])->name('sites.create');
Route::post('sites', [SiteController::class, 'store'])->name('sites.store');
 
//Route::get('sites/{siteId}/orders', [SiteController::class, 'fetchOrders'])->name('sites.orders');
Route::get('sites/{id}', [SiteController::class, 'show'])->name('sites.show');
Route::get('/sites/{id}/orders', [SiteController::class, 'show'])->name('sites.show');
Route::post('/sites/{id}/update-status', [SiteController::class, 'updateStatus'])->name('sites.updateStatus');
Route::get('/ajoutercompagne', [EvenementsController::class, 'createCompagne'])->name('themes.ajoutercompagne');
Route::get('/dashboard_evenement', [EvenementsController::class, 'showDashboard'])->name('evenements.dashboard');
Route::get('/dashboard_marque', [RedirectionsController::class, 'dashboardMarqueRoi'])->name('dashboard.marque');
/*Order Woocommerce */
Route::post('/sites//{id}/orders', [SiteController::class, 'storeRedirection'])->name('sites.redirection');
/*Mail Box */
Route::resource('mailboxes', MailboxController::class);
Route::get('mailboxes/{mailboxId}/fetch', [MailboxController::class, 'fetchEmails'])->name('mailboxes.fetch');
Route::delete('mailboxes/{mailboxId}/emails/{mailId}', [MailboxController::class, 'deleteEmail'])->name('mailboxes.deleteEmail');
 
/*edit profile */
Route::get('profile/edit', [EquipeController::class, 'edit'])->name('profile.edit');
Route::post('profile/update', [EquipeController::class, 'update'])->name('profile.update');
/*Dashbaord Revendeurs*/

Route::get('dashboard_revendeur', [RevendeursController::class, 'dashboard'])->name('revendeurs.dashboard');
Route::get('dashboard_produit', [ProduitController::class, 'dashboardProduit'])->name('dashboard.produit');
 
 
/*Redirection */
 
Route::get('dashboard_redirection', [RedirectionsController::class, 'dashboardRedirection'])
    ->name('dashboard.redirection');

/*Marque */
Route::get('marque', [RedirectionsController::class, 'getRedirectionStatisticsBrand'])->name('marque.statistics');
// Routes pour les différentes pages de redirections
Route::get('encours', [RedirectionsController::class, 'enCours'])->name('redirections.enCours');
Route::get('annule', [RedirectionsController::class, 'annulees'])->name('redirections.annulees');
Route::get('valider', [RedirectionsController::class, 'validees'])->name('redirections.validees');
Route::get('dashboard_tv', [TvDashbaordController::class, 'index'])->name('tv.dashboard');
Route::get('/produitsproposer', [RedirectionsController::class, 'proposeProduitsParSaison'])->name('proposeProduitsParSaison');
 
Route::get('brands/{id}/toggle-status', [BrandController::class, 'toggleStatus'])->name('brands.toggleStatus');
Route::put('/brands/{id}', [BrandController::class, 'update'])->name('brands.update');
Route::delete('/brands/{id}', [BrandController::class, 'destroy'])->name('brands.destroy');
Route::get('/brands/{id}/change-etat', [BrandController::class, 'changeEtat'])->name('brand.changeEtat');
//Route::put('/brand/{id}/etat', [BrandController::class, 'updateEtat'])->name('brand.changeEtat');
Route::patch('/produit/{id}/updateEtat', [ProduitController::class, 'updateEtat'])->name('produit.updateEtat');
Route::get('produit/{id}/edit', [ProduitController::class, 'edit'])->name('produit.edit');
Route::put('produit/{id}', [ProduitController::class, 'update'])->name('produits.update');
// Route to show the edit form for a revendeur

// Route to update a revendeur
Route::put('/revendeurs/{id}/change-etat', [RevendeursController::class, 'changeEtat'])->name('revendeurs.changeEtat');
//Route::put('/revendeurs/update/{id}', [RevendeursController::class, 'update'])->name('revendeurs.update');

Route::post('/equipes/{id}/affecter-marque', [EquipeController::class, 'affecter'])->name('equipes.affecter-marque');
Route::get('/statut_redirection', [RedirectionsController::class, 'getStateRedirection']);
//Route::get('/statut_redirection', [RedirectionsController::class, 'showStats'])->name('redirections.showStats');
//Mail 

Route::get('/themes/smtp/config', [MailSettingController::class, 'showForm'])->name('mailSettings.form');
Route::post('/themes/smtp/config', [MailSettingController::class, 'saveSettings'])->name('mailSettings.save');

Route::get('/inbox', [MailSettingController::class, 'inbox'])->name('mail.inbox');
Route::get('/inbox/{id?}', [MailSettingController::class, 'inbox']);
Route::post('/send-email', [MailSettingController::class, 'sendEmail'])->name('send.email');


// Affichage du formulaire de configuration


// Affichage de la boîte de réception
Route::get('/inbox', [MailSettingController::class, 'inbox'])->name('mail.inbox');

// Envoi d'un e-mail
 Route::post('/inbox', [MailSettingController::class, 'sendEmail'])->name('mail.send');

// Sauvegarde d'un brouillon
Route::post('/inbox', [MailSettingController::class, 'saveDraft'])->name('mail.saveDraft');

// Affichage des mails par dossier
Route::get('/inbox/{folder}', [MailSettingController::class, 'inboxView'])->name('mail.inboxView');

// Suppression d'un mail
Route::delete('/delete-mail/{id}', [MailSettingController::class, 'deleteMail'])->name('mail.delete');
//Route::get('/inbox/message/{id}', [MailboxController::class, 'showDetails']);
//Evenement

Route::post('/ajouterevenement', [EvenementsController::class, 'store'])->name('ajouterevenement.store');
Route::patch('/change-revendeur', [RedirectionsController::class, 'changeRevendeur'])->name('change.revendeur');
 //Notification Redirection  sans valider
 Route::get('/pending-redirections', [RedirectionsController::class, 'getPendingRedirections']);
// Route pour récupérer les notifications non validées
Route::get('/notifications', function() {
    $notifications = DB::table('redirections')
        ->where('etat_red', '!=', 0)
        ->where('created_at', '<', now()->subMinutes(30))
        ->get();
    return response()->json($notifications); // Retourne les notifications en JSON
});
 
Route::get('/redirections/{id}', [RedirectionsController::class, 'show'])->name('redirection.show');
Route::post('/listeredirection/{ref_redirection}', [RedirectionsController::class, 'updateRedirection'])->name('redirections.update');
//Route::get('/dashboard_revendeur', [RevendeursController::class, 'dashboardGraphicRevendeur']);

//Route::get('/dashboard_marque', [BrandController::class, 'DashboardMarques'])->name('dashboard.marque');
 // Formulaire de mot de passe oublié
Route::get('/password-oublier', [EquipeController::class, 'showForgotPasswordForm'])->name('password.form');

// Envoi du mot de passe par e-mail
Route::post('/password-oublier', [EquipeController::class, 'sendPasswordByEmail'])->name('password.send');

//report marques

Route::get('/report', [RedirectionsController::class, 'showReport'])->name('report');
Route::put('/redirections/{reference}', [RedirectionsController::class, 'update'])->name('redirections.update');

//Commercail

 Route::put('/commercials/{id}', [CommercialController::class, 'update'])->name('commercials.update');
//Emails de redirection  

// Route pour afficher la liste des emails envoyés
Route::get('/emails-redirections', [RedirectionsController::class, 'listEmails'])->name('emails.redirections');

// Export

 
 