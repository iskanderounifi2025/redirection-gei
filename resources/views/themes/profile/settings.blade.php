<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard detail</title>
    @include('themes.style')  <!-- Link to external CSS styles -->
</head>

<body>
    <div class="container-scroller">
        @include('themes.header')  <!-- Header content -->
        @include('themes.sideleft')  <!-- Sidebar content -->

        <div class="main-panel">
            <div class="content-wrapper">
                <!-- Date Range Section -->
                <div class="row mb-4">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Paramètres du profil</h4>

                                <!-- Success Message after save -->
                                @if (session('success'))
                                    <div class="alert alert-success mb-4">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <!-- Vertical Tabs -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <ul class="nav flex-column nav-pills" id="settingsTabs" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link active" id="messaging-tab" data-bs-toggle="tab" href="#messaging" role="tab" aria-controls="messaging" aria-selected="true">
                                                    <i class="ti-email"></i> Messagerie
                                                </a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" id="general-tab" data-bs-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="false">
                                                    <i class="ti-settings"></i> Paramètres généraux
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-9">
                                        <!-- Tab Content -->
                                        <div class="tab-content mt-4" id="settingsTabsContent">

                                            <!-- Messaging Tab -->
                                            <div class="tab-pane fade show active" id="messaging" role="tabpanel" aria-labelledby="messaging-tab">
                                                <h2 class="card-title">Paramètres de messagerie</h2>
                                                <form method="POST" action="{{ route('saveSettings') }}">
                                                    @csrf

                                                    <div class="mb-3">
                                                        <label for="MAILER">Mailer</label>
                                                        <input type="text" id="MAILER" name="MAILER" class="form-control" value="{{ old('MAILER', env('MAIL_MAILER')) }}" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="SMTP_HOST">Hôte</label>
                                                        <input type="text" id="SMTP_HOST" name="SMTP_HOST" class="form-control" value="{{ old('SMTP_HOST', env('MAIL_HOST')) }}" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="SMTP_PORT">Port</label>
                                                        <input type="number" id="SMTP_PORT" name="SMTP_PORT" class="form-control" value="{{ old('SMTP_PORT', env('MAIL_PORT')) }}" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="SMTP_USERNAME">Nom d'utilisateur</label>
                                                        <input type="text" id="SMTP_USERNAME" name="SMTP_USERNAME" class="form-control" value="{{ old('SMTP_USERNAME', env('MAIL_USERNAME')) }}" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="SMTP_PASSWORD">Mot de passe</label>
                                                        <input type="password" id="SMTP_PASSWORD" name="SMTP_PASSWORD" class="form-control" value="{{ old('SMTP_PASSWORD', env('MAIL_PASSWORD')) }}" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="SMTP_ENCRYPTION">Chiffrement</label>
                                                        <select id="SMTP_ENCRYPTION" name="SMTP_ENCRYPTION" class="form-control" required>
                                                            <option value="ssl" {{ old('SMTP_ENCRYPTION', env('MAIL_ENCRYPTION')) == 'ssl' ? 'selected' : '' }}>SSL</option>
                                                            <option value="tls" {{ old('SMTP_ENCRYPTION', env('MAIL_ENCRYPTION')) == 'tls' ? 'selected' : '' }}>TLS</option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="FROM_ADDRESS">Adresse de l'expéditeur</label>
                                                        <input type="email" id="FROM_ADDRESS" name="FROM_ADDRESS" class="form-control" value="{{ old('FROM_ADDRESS', env('MAIL_FROM_ADDRESS')) }}" required>
                                                    </div>

                                                    <button type="submit" class="btn btn-success">Sauvegarder les modifications</button>
                                                </form>
                                            </div>

                                            <!-- General Settings Tab -->
                                            <div class="tab-pane fade" id="general" role="tabpanel" aria-labelledby="general-tab">
                                                <h2 class="card-title">⚙️ Paramètres généraux</h2>
                                                <form method="POST" action="{{ route('saveGeneralSettings') }}">
                                                    @csrf

                                                    <div class="mb-3">
                                                        <label for="COMPANY_NAME">Nom de l'entreprise</label>
                                                        <input type="text" id="COMPANY_NAME" name="COMPANY_NAME" class="form-control" value="{{ old('COMPANY_NAME', env('COMPANY_NAME')) }}" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="COMPANY_EMAIL">Email de l'entreprise</label>
                                                        <input type="email" id="COMPANY_EMAIL" name="COMPANY_EMAIL" class="form-control" value="{{ old('COMPANY_EMAIL', env('COMPANY_EMAIL')) }}" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="TIMEZONE">Fuseau horaire</label>
                                                        <input type="text" id="TIMEZONE" name="TIMEZONE" class="form-control" value="{{ old('TIMEZONE', env('APP_TIMEZONE')) }}" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="PHONE">Téléphone</label>
                                                        <input type="text" id="PHONE" name="PHONE" class="form-control" value="{{ old('PHONE', env('COMPANY_PHONE')) }}" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="ADDRESS">Adresse</label>
                                                        <input type="text" id="ADDRESS" name="ADDRESS" class="form-control" value="{{ old('ADDRESS', env('COMPANY_ADDRESS')) }}" required>
                                                    </div>

                                                    <button type="submit" class="btn btn-success">Sauvegarder les modifications</button>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- content-wrapper ends -->
            </div> <!-- main-panel ends -->
        </div> <!-- container-scroller ends -->

        <!-- Include Bootstrap 5 JS -->
        @include('themes.js') <!-- Include external JavaScript -->
    </div>
</body>

</html>
