<?php

namespace App\Notifications;


namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Redirection;

class RedirectionUpdated extends Notification
{
    protected $redirection;

    public function __construct(Redirection $redirection)
    {
        $this->redirection = $redirection;
    }

    public function via($notifiable)
    {
        return ['database', 'mail']; // Vous pouvez ajouter d'autres canaux ici, comme 'database', 'mail', etc.
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'L\'état de la redirection pour la référence ' . $this->redirection->reference . ' a été mis à jour.',
            'redirection_id' => $this->redirection->id,
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('L\'état de la redirection a été mis à jour.')
                    ->action('Voir les détails', url('/redirections/' . $this->redirection->reference))
                    ->line('Merci d\'avoir utilisé notre application!');
    }
}
