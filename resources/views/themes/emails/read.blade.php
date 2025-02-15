<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lire Email</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Contenu de l'email</h1>
        <div class="card">
            <div class="card-body">
                <!-- Affiche le contenu de l'email, encodé en HTML -->
                <p>{!! nl2br(e($body)) !!}</p>
            </div>
        </div>
        <a href="{{ route('emails.index') }}" class="btn btn-primary mt-4">Retour à la liste des emails</a>
    </div>
</body>
</html>
