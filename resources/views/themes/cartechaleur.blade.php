<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Carte de Chaleur des Ventes</title>

    <!-- Plugins: CSS -->
    @include('themes.style')

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Google Charts Loader -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <style>
        #main {
            width: 100%;
            height: 600px;
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        @include('themes.header')
        @include('themes.sideleft')

        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <!-- Section Carte -->
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <p class="card-title">Carte de Chaleur des Ventes</p>
                                <div id="map" style="height: 600px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Section Graphique à Barres -->
                  
                </div>
            </div>
        </div>
        @include('themes.js')
    </div>

    <!-- Scripts pour Leaflet et Google Charts -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Récupération des données de redirection passées du backend (Laravel)
        const redirections = @json($redirections);

        // Initialisation de la carte Leaflet
        const map = L.map('map').setView([36.8065, 10.1815], 8); // Centré sur Tunis

        // Ajouter la couche OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Fonction pour géocoder une adresse avec Nominatim (OpenStreetMap)
        async function geocode(address) {
            const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`;
            try {
                const response = await fetch(url);
                const data = await response.json();
                if (data.length > 0) {
                    return { lat: parseFloat(data[0].lat), lon: parseFloat(data[0].lon) };
                } else {
                    console.error(`Adresse introuvable : ${address}`);
                    return null;
                }
            } catch (error) {
                console.error(`Erreur lors du géocodage pour l'adresse ${address}:`, error);
                return null;
            }
        }

        // Ajouter les marqueurs pour chaque redirection
        async function addMarkers() {
            for (const redirection of redirections) {
                const { client_adresse, nombre_clients } = redirection;

                // Géocoder l'adresse
                const location = await geocode(client_adresse);
                if (location) {
                    // Ajouter un marqueur sur la carte
                    L.marker([location.lat, location.lon])
                        .addTo(map)
                        .bindPopup(`<strong>${client_adresse}</strong><br>Clients : ${nombre_clients}`);
                }
            }
        }

        // Charger les marqueurs sur la carte
        addMarkers();
    </script>
 <script type="text/javascript">
    // Charger les packages nécessaires de Google Charts
    google.charts.load('current', {
        packages: ['corechart', 'bar']
    });

    google.charts.setOnLoadCallback(drawChart);

  
</script>

</body>

</html>
