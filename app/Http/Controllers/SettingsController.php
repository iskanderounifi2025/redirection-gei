<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    // Méthode pour enregistrer les paramètres de messagerie
    public function saveSettings(Request $request)
    {
        // Valider les données du formulaire
        $validated = $request->validate([
            'MAILER' => 'required|string',
            'SMTP_HOST' => 'required|string',
            'SMTP_PORT' => 'required|integer',
            'SMTP_USERNAME' => 'required|string',
            'SMTP_PASSWORD' => 'required|string',
            'SMTP_ENCRYPTION' => 'required|string',
            'FROM_ADDRESS' => 'required|email',
        ]);

        // Enregistrer les paramètres dans le fichier .env
        $this->updateEnvFile([
            'MAIL_MAILER' => $validated['MAILER'],
            'MAIL_HOST' => $validated['SMTP_HOST'],
            'MAIL_PORT' => $validated['SMTP_PORT'],
            'MAIL_USERNAME' => $validated['SMTP_USERNAME'],
            'MAIL_PASSWORD' => $validated['SMTP_PASSWORD'],
            'MAIL_ENCRYPTION' => $validated['SMTP_ENCRYPTION'],
            'MAIL_FROM_ADDRESS' => $validated['FROM_ADDRESS'],
        ]);

        // Rediriger avec un message de succès
        return back()->with('success', 'Paramètres de messagerie enregistrés avec succès.');
    }

    // Méthode pour enregistrer les paramètres généraux
    public function saveGeneralSettings(Request $request)
    {
        // Valider les données du formulaire
        $validated = $request->validate([
            'COMPANY_NAME' => 'required|string',
            'COMPANY_EMAIL' => 'required|email',
            'TIMEZONE' => 'required|string',
            'PHONE' => 'required|string',
            'ADDRESS' => 'required|string',
        ]);

        // Enregistrer les paramètres dans le fichier .env
        $this->updateEnvFile([
            'COMPANY_NAME' => $validated['COMPANY_NAME'],
            'COMPANY_EMAIL' => $validated['COMPANY_EMAIL'],
            'APP_TIMEZONE' => $validated['TIMEZONE'],
            'COMPANY_PHONE' => $validated['PHONE'],
            'COMPANY_ADDRESS' => $validated['ADDRESS'],
        ]);

        // Rediriger avec un message de succès
        return back()->with('success', 'Paramètres généraux enregistrés avec succès.');
    }

    // Méthode pour mettre à jour le fichier .env
    private function updateEnvFile(array $data)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        foreach ($data as $key => $value) {
            $str = preg_replace('/^' . $key . '=.*/m', $key . '=' . $value, $str);
        }

        file_put_contents($envFile, $str);
    }
}
