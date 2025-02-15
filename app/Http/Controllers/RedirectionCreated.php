<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MailRedirectionController extends Controller
{
    //
}
namespace App\Mail;

use App\Models\Redirection;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RedirectionCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $redirection;

    /**
     * Crée une nouvelle instance du mailable.
     */
    public function __construct(Redirection $redirection)
    {
        $this->redirection = $redirection;
    }

    /**
     * Construit le message.
     */
    public function build()
    {
        return $this->subject('Nouvelle Redirection Créée')
                    ->view('emails.redirection_created')
                    ->with('redirection', $this->redirection);
    }
}
