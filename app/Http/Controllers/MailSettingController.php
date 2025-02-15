<?php

namespace App\Http\Controllers;

use App\Models\MailSetting;
use App\Models\SentMail;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;
 use Exception;

class MailSettingController extends Controller
{
    public function showForm()
    {
        return view('mailSettingsForm');
    }

    public function inbox($id_team)
    {
        set_time_limit(300); // Augmenter le temps d'exécution à 5 minutes
    
        // Validation préalable des paramètres
        $settings = MailSetting::where('id_team', $id_team)->first();
        if (!$settings) {
            return response()->json(['error' => 'Paramètres IMAP non configurés pour cette équipe.'], 400);
        }

        try {
            // Connexion au serveur IMAP
            $imapStream = @imap_open("{{$settings->imap_host}:{$settings->imap_port}/imap/ssl}INBOX", $settings->imap_username, $settings->imap_password);
    
            if (!$imapStream) {
                throw new Exception(imap_last_error());
            }

            // Recherche des emails dans la boîte de réception
            $emails = imap_search($imapStream, 'ALL', SE_UID);
            if (!$emails) {
                return response()->json(['error' => 'Aucun email trouvé.'], 500);
            }

            // Limiter à 50 emails récents
            $emails = array_slice(array_reverse($emails), 0, 50);

            // Tableau pour stocker les messages
            $messages = [];
            foreach ($emails as $emailNumber) {
                $overview = imap_fetch_overview($imapStream, $emailNumber, 0);
                $structure = imap_fetchstructure($imapStream, $emailNumber);

                $message = null;

                // Vérification du type de message et récupération du corps en fonction du format
                if (isset($structure->parts)) {
                    foreach ($structure->parts as $idx => $subpart) {
                        if ($subpart->type == 0) { // Texte brut
                            $message = imap_fetchbody($imapStream, $emailNumber, $idx + 1);
                            break;
                        }
                    }
                }

                // Décoder le message
                $message = quoted_printable_decode($message);

                // Ajouter l'email au tableau des messages
                $messages[] = [
                    'id' => $emailNumber,
                    'subject' => $overview[0]->subject ?? '(Pas de sujet)',
                    'from' => $overview[0]->from ?? '(Inconnu)',
                    'date' => $overview[0]->date ?? '(Date inconnue)',
                    'body' => $message ?? '(Pas de contenu)',
                ];
            }

            // Retourner la vue avec les messages
            return view('themes.smtp.inbox', compact('messages', 'id_team'));

        } catch (Exception $e) {
            Log::error("Erreur IMAP pour l'équipe $id_team : " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        } finally {
            // Assurez-vous de fermer la connexion IMAP
            if (isset($imapStream) && $imapStream) {
                imap_close($imapStream);
            }
        }
    }
     
    

    
    

    public function saveSettings(Request $request)
    {
        $validated = $request->validate([
            'smtp_host' => 'required',
            'smtp_port' => 'required|integer',
            'smtp_username' => 'required|email',
            'smtp_password' => 'required',
            'smtp_encryption' => 'required',
            'imap_host' => 'required',
            'imap_port' => 'required|integer',
            'imap_username' => 'required|email',
            'imap_password' => 'required',
'id_team'=>'required|integer',
        ]);

        MailSetting::create($validated);

        return redirect()->route('themes.smtp.config')->with('success', 'Paramètres enregistrés avec succès.');
    } 

    public function sendEmail($id_team)
{
    set_time_limit(300); // Augmenter le temps d'exécution à 5 minutes

    // Validation préalable des paramètres
    $settings = MailSetting::where('id_team', $id_team)->first();
    if (!$settings) {
        return response()->json(['error' => 'Paramètres SMTP non configurés pour cette équipe.'], 400);
    }

    try {
        // Création de l'instance PHPMailer
        $mail = new PHPMailer(true);

        // Paramètres SMTP
        $mail->isSMTP();
        $mail->Host = $settings->smtp_host;  // Exemple : smtp.ovh.net
        $mail->SMTPAuth = true;
        $mail->Username = $settings->smtp_username; // Votre adresse email
        $mail->Password = $settings->smtp_password; // Votre mot de passe
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $settings->smtp_port; // Exemple : 587 pour TLS

        // Expéditeur et destinataire
        $mail->setFrom($settings->smtp_username, 'Votre Nom');
        $mail->addAddress('destinataire@example.com', 'Nom du destinataire'); // Ajouter le destinataire
        $mail->addReplyTo($settings->smtp_username, 'Votre Nom');

        // Contenu de l'email
        $mail->isHTML(true);
        $mail->Subject = 'Sujet de votre email';
        $mail->Body    = 'Ceci est le <b>corps</b> de votre message.';

        // Envoi de l'email
        $mail->send();
        return response()->json(['message' => 'Email envoyé avec succès.']);
    } catch (Exception $e) {
        // Log l'erreur si l'email n'a pas pu être envoyé
        Log::error("Erreur d'envoi d'email pour l'équipe $id_team : " . $mail->ErrorInfo);
        return response()->json(['error' => 'Erreur lors de l\'envoi de l\'email : ' . $mail->ErrorInfo], 500);
    }
}
    
    
    public function sentInbox()
{
    // Récupérer les e-mails envoyés
    $sentMails = SentMail::all();

    return view('themes.smtp.inbox', compact('sentMails'));
}

    public function saveDraft(Request $request)
    {
        $validated = $request->validate([
            'to' => 'nullable|email',
            'subject' => 'nullable|string|max:255',
            'body' => 'nullable|string',
        ]);

        Message::create(array_merge($validated, ['folder' => 'drafts']));

        return response()->json(['success' => 'Brouillon sauvegardé avec succès.']);
    }

    public function inboxView($folder = 'inbox')
    {
        $messages = Message::where('folder', $folder)->get();

        return view('themes.smtp.inbox', [
            'viewType' => $folder,
            'messages' => $messages,
        ]);
    }

    // Nouvelle méthode pour supprimer un mail
    public function deleteMail($id)
    {
        $message = Message::find($id);

        if (!$message) {
            return response()->json(['error' => 'Message introuvable.'], 404);
        }

        // Supprimer le message de la base de données
        $message->delete();

        return response()->json(['success' => 'Message supprimé avec succès.']);
    }

     
    public function showDetails($id)
{
    $settings = MailSetting::first();

    if (!$settings) {
        return response()->json(['error' => 'Paramètres IMAP non configurés.'], 400);
    }

    $host = $settings->imap_host;
    $port = $settings->imap_port;
    $username = $settings->imap_username;
    $password = $settings->imap_password;

    $imapStream = @imap_open("{{$host}:{$port}/imap/ssl}INBOX", $username, $password);

    if (!$imapStream) {
        $error = imap_last_error();
        Log::error("Erreur de connexion IMAP: " . $error);
        return response()->json(['error' => "Impossible de se connecter au serveur IMAP : $error"], 500);
    }

    $overview = imap_fetch_overview($imapStream, $id, 0);
    $structure = imap_fetchstructure($imapStream, $id);
    $message = null;

    if (isset($structure->parts)) {
        foreach ($structure->parts as $idx => $subpart) {
            if ($subpart->type == 0) { // Texte brut
                $message = imap_fetchbody($imapStream, $id, $idx + 1);
                break;
            }
        }
    }

    $message = quoted_printable_decode($message);

    imap_close($imapStream);

    return response()->json([
        'success' => true,
        'message' => [
            'subject' => $overview[0]->subject ?? '(Pas de sujet)',
            'from' => $overview[0]->from ?? '(Inconnu)',
            'date' => $overview[0]->date ?? '(Date inconnue)',
            'body' => $message ?? '(Pas de contenu)',
        ]
    ]);
}

    
}
    
 
