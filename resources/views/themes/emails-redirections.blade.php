<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Liste des Emails</title>
  @include('themes.style')
  <style>
      .email-container {
      display: flex;
      height: 100vh;
    }

    /* Sidebar (liste des emails) */
    .email-list {
      width: 30%;
      background: #fff;
      border-right: 1px solid #eee;
      overflow-y: auto;
    }

    .email-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 15px;
      border-bottom: 1px solid #eee;
      transition: background 0.2s ease;
      cursor: pointer;
    }

    .email-item:last-child {
      border-bottom: none;
    }

    .email-item:hover,
    .email-item.active {
      background: #f1f1f1;
    }

    .email-info {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .email-info .icon {
      background-color: #4CAF50;
      color: #fff;
      width: 40px;
      height: 40px;
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 50%;
      font-size: 18px;
      font-weight: bold;
    }

    .email-details {
      flex: 1;
    }

    .email-details h5 {
      margin: 0;
      font-size: 16px;
      color: #333;
      font-weight: bold;
    }

    .email-details p {
      margin: 5px 0 0;
      font-size: 14px;
      color: #777;
    }

    .email-date {
      font-size: 12px;
      color: #999;
      white-space: nowrap;
    }

    /* Right panel (details des emails) */
    .email-content {
      flex: 1;
      padding: 20px;
      background: #f9f9f9;
      overflow-y: auto;
    }

    .email-content h2 {
      margin-bottom: 20px;
      font-size: 22px;
      color: #333;
    }

    .email-content p {
      line-height: 1.6;
      color: #555;
    }

    .email-content .content {
      margin-top: 10px;
      background: #fff;
      padding: 15px;
      border-radius: 8px;
    }
   </style>
</head>

<body>
  <div class="container-scroller">
    @include('themes.header')
    @include('themes.sideleft')

    <div class="main-panel">
      <div class="content-wrapper">
      <div class="email-container">
    <!-- Liste des emails -->
    <div class="email-list">
      @foreach ($emailsEnvoyes as $email)
        <div class="email-item" onclick="showEmailDetails({{ $email->id }})" id="emailItem{{ $email->id }}">
          <div class="email-info">
            <div class="icon">{{ strtoupper(substr($email->sujet, 0, 1)) }}</div>
            <div class="email-details">
              <h5>{{ $email->sujet }}</h5>
              <p>De : {{ $email->email_expediteur }}</p>
            </div>
          </div>
          <div class="email-date">{{ $email->created_at->format('d M Y, H:i') }}</div>
        </div>
      @endforeach
    </div>

    <!-- Détails des emails -->
    <div class="email-content" id="emailDetails">
      <h2>Sélectionnez un email</h2>
      <p>Cliquez sur un email pour afficher ses détails ici.</p>
    </div>
  </div>

  <script>
    const emails = @json($emailsEnvoyes);

    function showEmailDetails(emailId) {
      const email = emails.find(e => e.id === emailId);

      // Gérer la sélection active
      document.querySelectorAll('.email-item').forEach(item => item.classList.remove('active'));
      document.getElementById(`emailItem${emailId}`).classList.add('active');

      // Afficher les détails
      const emailDetails = `
        <h2>${email.sujet}</h2>
        <p><strong>Référence :</strong> ${email.reference}</p>
        <p><strong>Expéditeur :</strong> ${email.email_expediteur}</p>
        <p><strong>Destinataires :</strong></p>
        <ul>
          ${JSON.parse(email.destinataires).map(dest => `<li>${dest}</li>`).join('')}
        </ul>
        <div class="content">
          ${email.contenu}
        </div>
        <p class="email-date">Envoyé le : ${new Date(email.created_at).toLocaleString()}</p>
      `;

      document.getElementById('emailDetails').innerHTML = emailDetails;
    }
  </script>
      @include('themes.js')
    </div>
  </div>
</body>

</html>
