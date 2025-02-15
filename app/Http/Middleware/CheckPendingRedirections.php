<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Redirection;
use Illuminate\Support\Facades\Session;

class CheckPendingRedirections
{
    /**
     * Vérifie les redirections non validées et les ajoute à la session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérification des redirections non validées créées il y a plus de 30 minutes
        $redirectionsNonValide = Redirection::where('etat_red', '!=', 1)  // Etat non validé
            ->where('created_at', '<', now()->subMinutes(30))  // Créées il y a plus de 30 minutes
            ->get();

        // Si des redirections non validées existent, on les notifie
        if ($redirectionsNonValide->isNotEmpty()) {
            Session::flash('warning', 'Il y a des redirections non validées depuis plus de 30 minutes. Veuillez les valider.');
        }

        return $next($request);
    }
}
