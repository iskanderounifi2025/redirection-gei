<!-- resources/views/mailboxes/index.blade.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boîte aux mails</title>
</head>
<body>
    <h1>Liste des Mails</h1>

    @if($mails->isEmpty())
        <p>Aucun mail trouvé.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Objet</th>
                    <th>Expéditeur</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mails as $mail)
                    <tr>
                        <td>{{ $mail->subject }}</td>
                        <td>{{ $mail->sender }}</td>
                        <td>{{ $mail->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
