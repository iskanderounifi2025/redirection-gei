<?php
 namespace App\Http\Controllers;

 use Illuminate\Http\Request;
 
 class MailController extends Controller
 {
     /**
      * Fetch and display the list of emails.
      */
     public function index()
     {
         // Gmail IMAP credentials from .env
         $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
         $username = env('GMAIL_USERNAME'); // Gmail address
         $password = env('GMAIL_PASSWORD'); // App password
 
         // Connect to Gmail
         try {
             $inbox = imap_open($hostname, $username, $password);
         } catch (\Exception $e) {
             return back()->withErrors('Failed to connect to Gmail: ' . imap_last_error());
         }
 
         // Fetch emails
         $emails = imap_search($inbox, 'ALL');
         $output = [];
 
         if ($emails) {
             // Sort emails in descending order
             rsort($emails);
 
             // Fetch details of the latest 10 emails
             foreach (array_slice($emails, 0, 10) as $email_number) {
                 $overview = imap_fetch_overview($inbox, $email_number, 0)[0];
                 $output[] = [
                     'id' => $email_number,
                     'subject' => $overview->subject ?? '(No Subject)',
                     'from' => $overview->from ?? '(Unknown Sender)',
                     'date' => $overview->date ?? '(Unknown Date)',
                 ];
             }
         }
 
         // Close the connection
         imap_close($inbox);
 
         // Pass emails to the view
         return view('emails.index', ['emails' => $output]);
     }
 
     /**
      * Fetch and display the details of a single email.
      */
      public function read($id)
      {
          // Gmail IMAP credentials
          $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
          $username = 'iskander.ounifi.gei@gmail.com'; // Remplacez par votre adresse Gmail
          $password = '123456789Azerty';   // Remplacez par votre mot de passe ou mot de passe d'application
      
          // Connectez-vous à Gmail
          $inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Gmail: ' . imap_last_error());
      
          // Récupérez le corps de l'email
          $body = imap_body($inbox, $id);
      
          // Encodage pour garantir que le contenu est lisible dans la vue
          if ($body) {
              $body = $this->decodeImapBody($body);
          } else {
              $body = 'Le contenu de cet email n\'a pas pu être récupéré.';
          }
      
          // Fermez la connexion IMAP
          imap_close($inbox);
      
          // Transmettez le contenu à la vue
          return view('emails.read', ['body' => $body]);
      }
      
      /**
       * Décoder le contenu IMAP (corps de l'email)
       *
       * @param string $body
       * @return string
       */
      private function decodeImapBody($body)
      {
          // Vérifiez si le contenu est encodé en base64
          if (base64_encode(base64_decode($body, true)) === $body) {
              return base64_decode($body);
          }
      
          // Vérifiez si le contenu est encodé en quoted-printable
          if (quoted_printable_decode($body) !== $body) {
              return quoted_printable_decode($body);
          }
      
          // Retourne le contenu brut si aucun encodage spécifique n'est détecté
          return $body;
      }
      
 
}