<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox des Redirections</title>
    @include('themes.style')
</head>

<body>
    <div class="container-scroller">
        @include('themes.header')
        @include('themes.sideleft')

        <!-- Messages de succès et d'erreur -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Contenu principal -->
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-md-12 grid-margin">
                        <div class="row">
                            <!-- Sidebar gauche -->
                            <div class="col-lg-3 col-md-4">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="sidebar p-3">
                                            <h4 class="mb-4 text-center">Mailbox</h4>
                                            <ul class="nav flex-column">
                                                <li class="nav-item">
                                                    <a href="{{ route('mail.inbox') }}" class="nav-link active">
                                                        <i class="fas fa-inbox"></i> Inbox
                                                        <span class="badge badge-pill badge-primary float-end">{{ count($messages) }}</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#" class="nav-link">
                                                        <i class="fas fa-paper-plane"></i> Sent
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#" class="nav-link">
                                                        <i class="fas fa-archive"></i> Archived
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#" class="nav-link">
                                                        <i class="fas fa-trash"></i> Trash
                                                    </a>
                                                </li>
                                            </ul>
                                            <button class="btn btn-primary mt-4 w-100" data-bs-toggle="modal" data-bs-target="#composeModal">
                                                <i class="fas fa-plus"></i> Compose
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contenu principal -->
                            <div class="col-lg-9 col-md-8">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Boîte de réception</h4>
                                        <div class="col-md-12">
                                            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un produit...">
                                        </div>

                                        <div class="table-responsive mt-4">
                                            <table class="table table-striped" id="messageTable">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Expéditeur</th>
                                                        <th>Sujet</th>
                                                        <th>Date</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($messages as $key => $message)
                                                        <tr data-id="{{ $message['id'] }}">
                                                            <td>{{ $key + 1 }}</td>
                                                            <td>{{ $message['from'] ?? '(Inconnu)' }}</td>
                                                            <td>{{ $message['subject'] ?? '(Pas de sujet)' }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($message['date'])->format('d/m/Y H:i') }}</td>
                                                            <td>
                                                                <button class="btn btn-primary btn-sm viewMessageBtn" data-id="{{ $message['id'] }}" data-bs-toggle="modal" data-bs-target="#messageDetailModal">Voir</button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modale : Composer un email -->
    <div class="modal fade" id="composeModal" tabindex="-1" aria-labelledby="composeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="composeModalLabel">Nouveau Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form id="sendEmailForm" action="{{ route('send.email') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="recipient" class="form-label">Destinataire</label>
                        <input type="email" class="form-control" id="recipient" name="to" required>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Sujet</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="messageBody" class="form-label">Message</label>
                        <textarea class="form-control" id="messageBody" name="body" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Envoyer</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Modale : Détails d'un message -->
    <div class="modal fade" id="messageDetailModal" tabindex="-1" aria-labelledby="messageDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageDetailModalLabel">Détails du message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Sujet :</strong> <span id="modalSubject"></span></p>
                    <p><strong>De :</strong> <span id="modalFrom"></span></p>
                    <p><strong>Date :</strong> <span id="modalDate"></span></p>
                    <p><strong>Contenu :</strong></p>
                    <div id="modalBody" style="white-space: pre-wrap;"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    @include('themes.js')
</body>

</html>
