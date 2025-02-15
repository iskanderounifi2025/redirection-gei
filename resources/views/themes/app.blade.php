<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mon Application')</title>

    <!-- Fichiers CSS globaux -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery Toast CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Toast-plugin/1.3.2/jquery.toast.min.css">

    <!-- jQuery Toast JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Toast-plugin/1.3.2/jquery.toast.min.js"></script>

    <!-- Autres ressources CSS ou JS spécifiques -->
    @stack('styles')
</head>
<body>

    <!-- Navigation globale -->
    @include('partials.nav')

    <!-- Contenu principal -->
    <div class="container">
        @yield('content')  <!-- L'endroit où le contenu spécifique de la page sera injecté -->
    </div>

    <!-- Footer global -->
    @include('partials.footer')

    <!-- Scripts globaux -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Notifications (si présente) -->
    @if(session('notificationred'))
        <script>
            $(document).ready(function() {
                let message = "{{ session('notificationred') }}";
                let notificationType = "";

                if (message.includes("validée")) {
                    notificationType = 'success';
                } else if (message.includes("envoyée au revendeur")) {
                    notificationType = 'info';
                } else if (message.includes("annulée")) {
                    notificationType = 'danger';
                }

                $.toast({
                    heading: 'Mise à jour de la redirection',
                    text: message,
                    showHideTransition: 'slide',
                    icon: notificationType,
                    position: 'top-right',
                    stack: false,
                    hideAfter: 5000,
                    loaderBg: '#ff6849',
                });
            });
        </script>
    @endif

</body>
</html>
