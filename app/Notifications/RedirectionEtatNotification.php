<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RedirectionEtatNotification extends Notification
{
    use Queueable;

    private $redirection;

    // Injecte la redirection dans le constructeur
    public function __construct($redirection)
    {
        $this->redirection = $redirection;
    }
    // Utilise les notifications par email et flash
    public function via($notifiable)
    {
        return ['database'];
    }
     // Notification par base de données
     public function toDatabase($notifiable)
     {
         return [
             'message' => "La redirection de la référence {$this->redirection->reference} nécessite une validation.",
             'redirection_id' => $this->redirection->id,
             'etat_red' => $this->redirection->etat_red,
         ];
     }
    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
