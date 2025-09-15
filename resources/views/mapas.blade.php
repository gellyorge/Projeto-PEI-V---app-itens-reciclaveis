<!DOCTYPE html>
<html>
<head>
    <title>Mapa de Coleta</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map { height: 100vh; }
    </style>
</head>
<body>

<div id="map"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    // Inicializa o mapa
    var map = L.map('map').setView([-20.8449, -41.1118], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // Função para carregar centros de coleta da API
   async function carregarCentros() {
    try {
        const response = await fetch('/api/localizacoes'); // ou Blade route()
        const dados = await response.json();

        dados.data.forEach(local => {
            L.marker([local.lat, local.lng])
             .addTo(map)
             .bindPopup(local.nome);
        });
    } catch (error) {
        console.error('Erro ao carregar centros de coleta:', error);
    }
}


    // Chama a função para carregar os centros
    carregarCentros();

    // Geolocalização do usuário
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;

            L.circleMarker([lat, lng], {
                radius: 8,
                fillColor: "red",
                color: "red",
                weight: 1,
                opacity: 1,
                fillOpacity: 0.8
            }).addTo(map)
              .bindPopup("Você está aqui!")
              .openPopup();

            map.setView([lat, lng], 14);
        }, function() {
            alert("Não foi possível obter sua localização.");
        });
    } else {
        alert("Geolocalização não é suportada pelo seu navegador.");
    }
</script>

</body>
</html>
