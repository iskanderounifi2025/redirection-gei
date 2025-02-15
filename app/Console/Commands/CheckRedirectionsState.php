<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Redirection;
use App\Notifications\RedirectionEtatNotification;
use Carbon\Carbon;

class CheckRedirectionsState extends Command
{
    protected $signature = 'redirection:check-state';
    protected $description = 'Vérifie les redirections dont l\'état n\'est pas validé après 30 minutes et envoie une notification.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Récupérer les redirections non validées dont l'intervalle entre la création et maintenant est supérieur à 30 minutes
        $redirections = Redirection::where('etat_red', '!=', 1)
            ->where('created_at', '<=', Carbon::now()->subMinutes(30))
            ->get();

        foreach ($redirections as $redirection) {
            // Trouver l'utilisateur à notifier (par exemple, l'administrateur ou l'équipe)
            $user = User::find($redirection->id_team); // Assurez-vous que l'utilisateur est bien lié à cette redirection

            // Envoi de la notification
            if ($user) {
                $user->notify(new RedirectionEtatNotification($redirection));
                $this->info("Notification envoyée pour la redirection ID: {$redirection->id}");
            }
        }

        return 0;
    }
}
