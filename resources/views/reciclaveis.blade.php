<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Recicláveis</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <style>
        body {
            background-color: #f5f5f5;
        }
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
    </style>
</head>
<body>

<div class="container my-5">
    <h1 class="text-center mb-4">♻️ Itens Recicláveis</h1>

    <div id="lista-reciclaveis" class="row g-4">
        <!-- Os cards serão inseridos dinamicamente aqui -->
    </div>

    <!-- Paginação -->
    <div class="d-flex justify-content-center my-4">
        <button id="prevPage" class="btn btn-outline-success me-2">⬅️ Anterior</button>
        <button id="nextPage" class="btn btn-outline-success">Próximo ➡️</button>
    </div>
</div>

<script>
    let currentPage = 1;

    // Função para carregar os itens da API
    function carregarReciclaveis(page = 1) {
        axios.get(`/api/reciclaveis?page=${page}`)
            .then(response => {
                const data = response.data.data;
                const container = document.getElementById('lista-reciclaveis');
                container.innerHTML = '';

                if (data.length === 0) {
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

                // Atualiza os botões de paginação
                currentPage = response.data.meta.current_page;
                document.getElementById('prevPage').disabled = currentPage === 1;
                document.getElementById('nextPage').disabled = currentPage === response.data.meta.last_page;
            })
            .catch(error => {
                console.error('Erro ao carregar os itens:', error);
            });
    }

    // Eventos de paginação
    document.getElementById('prevPage').addEventListener('click', () => {
        if (currentPage > 1) {
            carregarReciclaveis(currentPage - 1);
        }
    });

    document.getElementById('nextPage').addEventListener('click', () => {
        carregarReciclaveis(currentPage + 1);
    });

    // Carrega os itens iniciais
    carregarReciclaveis();
</script>

</body>
</html>
