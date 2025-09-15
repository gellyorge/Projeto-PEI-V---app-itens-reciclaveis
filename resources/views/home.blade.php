<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa e Recicláveis</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }

        .tab-content {
            height: calc(100vh - 60px); /* espaço para a navbar */
            overflow-y: auto;
        }

        #map {
            width: 100%;
            height: 100%;
        }

        .navbar-bottom {
            height: 60px;
        }

        /* Cards Recicláveis */
        .card {
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease;
        }
        .card:hover {
            transform: scale(1.03);
        }
        .card img {
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        .card-title {
            color: #198754;
            font-weight: bold;
        }
        .tab-content {
    height: calc(100vh - 60px); /* espaço para a navbar */
    overflow-y: auto;
    padding-bottom: 70px; /* espaço extra para não ficar atrás da navbar */
}

/* Ajuste específico para telas pequenas */
@media (max-width: 576px) {
    .tab-content {
        padding-bottom: 100px; /* mais espaço em celulares */
    }
}

    </style>
</head>
<body>

<!-- Conteúdo -->
<div id="mapa-tab" class="tab-content" style="display:none;">
    <div id="map"></div>
</div>

<div id="reciclaveis-tab" class="tab-content" style="display:block;">
    <div class="container my-3">
        <h1 class="text-center mb-4">♻️ Itens Recicláveis</h1>
        <div id="lista-reciclaveis" class="row g-4"></div>

        <div class="d-flex justify-content-center my-4">
            <button id="prevPage" class="btn btn-outline-success me-2">⬅️ Anterior</button>
            <button id="nextPage" class="btn btn-outline-success">Próximo ➡️</button>
        </div>
    </div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand navbar-bottom fixed-bottom bg-light">
    <ul class="navbar-nav w-100 d-flex justify-content-around">
        <li class="nav-item">
            <a href="#" class="nav-link active" onclick="switchTab('reciclaveis-tab', this)">Recicláveis</a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link" onclick="switchTab('mapa-tab', this)">Mapa</a>
        </li>
    </ul>
</nav>

<script>
    let mapInitialized = false;
    let map;
    let currentPage = 1;

    // Alternar abas
    function switchTab(tabId, el) {
        document.querySelectorAll('.navbar-bottom .nav-link').forEach(link => link.classList.remove('active'));
        el.classList.add('active');

        document.querySelectorAll('.tab-content').forEach(tab => tab.style.display = 'none');
        const tab = document.getElementById(tabId);
        tab.style.display = 'block';

        if(tabId === 'mapa-tab' && !mapInitialized) {
            initMap();
            mapInitialized = true;
        } else if(tabId === 'mapa-tab') {
            map.invalidateSize();
        }
    }

    // Inicializa Leaflet
    function initMap() {
        map = L.map('map').setView([-20.8449, -41.1118], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        carregarCentros();

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

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
            });
        }
    }

    // Carrega centros de coleta
    async function carregarCentros() {
        try {
            const response = await fetch('/api/localizacoes');
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

    // Carregar Recicláveis
    function carregarReciclaveis(page = 1) {
        axios.get(`/api/reciclaveis?page=${page}`)
            .then(response => {
                const data = response.data.data;
                const container = document.getElementById('lista-reciclaveis');
                container.innerHTML = '';

                if(data.length === 0){
                    container.innerHTML = '<p class="text-center text-muted">Nenhum item encontrado.</p>';
                    return;
                }

                data.forEach(item => {
                    container.innerHTML += `
                        <div class="col-md-4 col-lg-3">
                            <div class="card h-100">
                                <img src="${item.imagem}" class="card-img-top" alt="${item.nome}" onerror="this.src='https://via.placeholder.com/300x200?text=Imagem+Indisponível'">
                                <div class="card-body text-center">
                                    <h5 class="card-title">${item.nome}</h5>
                                    <p class="card-text"><strong>Tipo:</strong> ${item.tipo}</p>
                                    <p class="card-text text-success"><strong>${item.valor}</strong></p>
                                </div>
                            </div>
                        </div>
                    `;
                });

                currentPage = response.data.meta.current_page;
                document.getElementById('prevPage').disabled = currentPage === 1;
                document.getElementById('nextPage').disabled = currentPage === response.data.meta.last_page;
            })
            .catch(error => console.error('Erro ao carregar os itens:', error));
    }

    document.getElementById('prevPage').addEventListener('click', () => {
        if(currentPage > 1) carregarReciclaveis(currentPage - 1);
    });
    document.getElementById('nextPage').addEventListener('click', () => {
        carregarReciclaveis(currentPage + 1);
    });

    // Inicializar Recicláveis
    document.addEventListener('DOMContentLoaded', () => carregarReciclaveis());
</script>

</body>
</html>
