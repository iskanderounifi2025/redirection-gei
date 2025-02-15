<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class RedirectionStateUpdated extends Notification
{
    use Queueable;

    protected $message;

    // Le message que nous envoyons avec la notification
    public function __construct($message)
    {
        $this->message = $message;
    }

    // Définir le canal de notification
    public function via($notifiable)
    {
        return ['database'];
    }

    // Configurer le message de la notification pour la base de données
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
            'url' => route('redirections.index')  // Par exemple, l'URL à rediriger
        ];
    }
}
