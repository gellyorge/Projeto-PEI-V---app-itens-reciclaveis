# ♻️ EcoValor – App Web de Conscientização e Venda de Materiais Recicláveis

## 📱 Sobre o Projeto

**EcoValor** é uma aplicação web desenvolvida com o framework **Laravel** e a engine de templates **Blade**, com o objetivo de promover a **conscientização sobre reciclagem** e facilitar o acesso a informações sobre os **preços atualizados dos materiais recicláveis**, além de **locais onde eles podem ser vendidos ou entregues**.

---

## 🎯 Objetivos

- Incentivar a reciclagem como fonte de renda e responsabilidade ambiental
- Informar a população sobre o valor dos resíduos recicláveis
- Mapear locais de venda ou entrega, como cooperativas, ferros-velhos e pontos de coleta

---

## 🧩 Funcionalidades

### 📈 Tela de Preços
- Mostra os preços atualizados dos principais materiais recicláveis:
  - Plástico
  - Papel
  - Vidro
  - Alumínio
  - Cobre, entre outros
- Pode ser atualizada manualmente ou com integração futura com APIs públicas

### 📍 Tela de Locais de Venda
- Lista de locais próximos onde os materiais podem ser vendidos ou entregues
- Informações exibidas:
  - Nome do local
  - Endereço
  - Tipo de material aceito
  - Contato e horário de funcionamento

---

## 🛠️ Tecnologias Utilizadas

- **Laravel** (Framework PHP)
- **Blade** (Engine de templates do Laravel)
- **Bootstrap** (Para responsividade e layout)
- **MySQL** ou **SQLite** (Banco de dados)
- **Laravel Migrations** (Para controle da estrutura do banco de dados)

---

## 🚀 Como Rodar o Projeto Localmente

### 1. Clonar o repositório

```bash
git clone https://github.com/seu-usuario/ecovalor.git
cd ecovalor
```

### 2. Instalar as dependências

```bash
composer install
```

### 3. Configurar o `.env`

Copie o arquivo `.env.example` para `.env` e configure:

```bash
cp .env.example .env
php artisan key:generate
```

Configure o banco de dados conforme sua máquina (ex: MySQL, SQLite, etc.)

### 4. Rodar as migrations

```bash
php artisan migrate
```

### 5. Iniciar o servidor de desenvolvimento

```bash
php artisan serve
```

Acesse: [http://localhost:8000](http://localhost:8000)

---

## 🤝 Contribuição

Sinta-se à vontade para abrir *issues* ou *pull requests* com sugestões, melhorias ou correções.

---

## 📄 Licença

Este projeto está licenciado sob a Licença MIT. Veja o arquivo `LICENSE` para mais informações.

---

## 🙋‍♂️ Desenvolvedor

**Gellyorge**  
📧 Email: [gellyorge23@gmail.com](mailto:gellyorge23@gmail.com)

