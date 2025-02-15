<?php 
use Illuminate\Mail\Mailable;

class RappelRedirectionMail extends Mailable
{
    public $resultats;

    public function __construct($resultats)
    {
        $this->resultats = $resultats;
    }

    public function build()
    {
        return $this->view('emails.recapitulatif_redirection')  // Utilisation de la vue Blade
                    ->with([
                        'resultats' => $this->resultats,
                    ])
                    ->subject('Récapitulatif des Redirections du ' . date('Y-m-d'));  // Sujet avec la date du jour
    }
}
