<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard des Redirections</title>
    @include('themes.style')
    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container-scroller">
        @include('themes.header')
        @include('themes.sideleft')

        <!-- Main Content -->
        <div class="main-panel">
        <div class="content-wrapper">
        <div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <!-- SMTP Configuration Section -->
            <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">
               Configuration SMTP</h5>

                <!-- Display success or error messages -->
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @elseif(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('mailSettings.save') }}" method="POST">
                @csrf
                    <div class="form-group">
                        <label for="smtp_host">SMTP Host</label>
                        <input type="text" name="smtp_host" class="form-control" required><br>

                        <label for="smtp_port">SMTP Port</label>
                        <input type="number" name="smtp_port" class="form-control" required><br>

                        <label for="smtp_username">SMTP Username</label>
                        <input type="email" name="smtp_username" class="form-control" required><br>

                        <label for="smtp_password">SMTP Password</label>
                        <input type="password" name="smtp_password" class="form-control" required><br>

                        <label for="smtp_encryption">SMTP Encryption</label>
                        <select name="smtp_encryption" class="form-control" required>
                            <option value="tls">TLS</option>
                            <option value="ssl">SSL</option>
                            <option value="none">None</option>
                        </select><br>

                        <label for="imap_host">IMAP Host</label>
                        <input type="text" name="imap_host" class="form-control" required><br>

                        <label for="imap_port">IMAP Port</label>
                        <input type="number" name="imap_port" class="form-control" required><br>

                        <label for="imap_username">IMAP Username</label>
                        <input type="email" name="imap_username" class="form-control" required><br>

                        <label for="imap_password">IMAP Password</label>
                        <input type="password" name="imap_password" class="form-control" required><br>
                        <div class="col-mb-12">
<input type="hidden" name="id_team" value="{{ $team->id }}">
</div>

                        <button type="submit" class="btn btn-primary mt-3">Save Settings</button>
                    </div>
                </form>
            </section>

            <!-- Last Received Emails Section -->
            
        </div>
    </div>

    @include('themes.js')
</body>

</html>
