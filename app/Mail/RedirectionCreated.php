<?php
 
namespace App\Mail;

use App\Models\Redirection;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RedirectionCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $redirection;

    public function __construct(Redirection $redirection)
    {
        $this->redirection = $redirection;
    }

    public function build()
    {
        return $this->subject('Nouvelle Redirection Créée')
                    ->view('themes.redirection_created'); // Assurez-vous que cette vue existe
    }
}
