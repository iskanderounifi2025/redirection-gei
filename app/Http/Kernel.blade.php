<?php 
namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Le tableau des middlewares globaux de l'application.
     *
     * Ces middlewares sont exécutés pour chaque requête.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,

        // Ajouter votre middleware ici
        \App\Http\Middleware\CheckPendingRedirections::class,  // <- Ajoutez cette ligne
    ];
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            app(App\Http\Controllers\NomDuController::class)->envoyerRecapitulatifJour();
        })->dailyAt('18:00');  // Envoie un email tous les jours à 18h
    }
 }
