<?php

// app/Http/Controllers/MailboxController.php
namespace App\Http\Controllers;
use Carbon\Carbon; // Utilisation correcte de la classe Carbon

use App\Models\Mailbox;
use Illuminate\Http\Request;
use PhpImap\Mailbox as ImapMailbox; // Nous utiliserons cette bibliothèque pour interagir avec la boîte aux lettres

class MailboxController extends Controller
{
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'host' => 'required',
            'port' => 'required|integer',
        ]);

        // Enregistrement dans la base de données
        Mailbox::create($validated);

        return redirect()->route('mailboxes.index');
    }

    public function fetchEmails($mailboxId)
    {
        $mailbox = Mailbox::find($mailboxId);

        $mail = new ImapMailbox(
            '{' . $mailbox->host . ':' . $mailbox->port . ($mailbox->ssl ? '/ssl' : '') . '}',
            $mailbox->email,
            $mailbox->password
        );

        // Récupérer les e-mails non lus
        $mailsIds = $mail->searchMailbox('UNSEEN');
        $emails = [];
        foreach ($mailsIds as $mailId) {
            $email = $mail->getMail($mailId);
            $emails[] = [
                'subject' => $email->subject,
                'from' => $email->fromAddress,
                'date' => $email->date,
                'body' => $email->textHtml
            ];
        }

        return view('emails.index', compact('emails'));
    }

    public function deleteEmail($mailboxId, $mailId)
    {
        $mailbox = Mailbox::find($mailboxId);

        $mail = new ImapMailbox(
            '{' . $mailbox->host . ':' . $mailbox->port . ($mailbox->ssl ? '/ssl' : '') . '}',
            $mailbox->email,
            $mailbox->password
        );

        $mail->deleteMail($mailId);

        return redirect()->route('mailboxes.fetch', ['mailboxId' => $mailboxId]);
    }
  // Cette méthode est appelée lorsqu'on accède à /mailboxes
    public function index()
    {
        // Par exemple, récupérer tous les mails dans la boîte aux lettres
        $mails = Mailbox::all();
        
        // Retourner une vue avec les mails récupérés
        return view('emails.index', compact('mails'));
    }

    // Autres méthodes du contrôleur
}
 
