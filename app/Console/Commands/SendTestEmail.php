<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendTestEmail extends Command
{
    protected $signature = 'email:test';
    protected $description = 'Envoyer un e-mail de test via MailHog';

    public function handle()
    {
        $email = 'test@example.com';
        try {
            Mail::raw('Ceci est un e-mail de test.', function ($message) use ($email) {
                $message->to($email)->subject('Test MailHog');
            });
            $this->info('E-mail envoyé avec succès à ' . $email);
        } catch (\Exception $e) {
            $this->error('Erreur : ' . $e->getMessage());
        }
    }
}
