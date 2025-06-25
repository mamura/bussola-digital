# Bússola Digital

Bússola Digital é uma aplicação web construída com Laravel. O projeto oferece um ambiente de desenvolvimento
containerizado com Docker e MySQL para facilitar a configuração local.

## Requisitos
- Docker e Docker Compose instalados
- Opcionalmente, PHP 8.2 caso deseje executar comandos fora dos containers

## Primeiros passos
1. Clone o repositório:
    ```bash
    git clone https://github.com/mamura/bussola-digital.git
    cd bussola-digital
    ```
2. Copie `.env.example` para `.env` e ajuste as variáveis.
3. Inicie os serviços:
    ```bash
    docker compose up -d
    ```
4. Instale as dependências e gere a chave da aplicação:
    ```bash
    docker compose exec app composer install
    docker compose exec app php artisan key:generate
    ```

A aplicação estará disponível em `http://localhost` ou na porta definida no `.env`.

## Comandos úteis
- `docker compose exec app php artisan migrate` executa as migrações.
- `docker compose exec app php artisan test` roda os testes.

## Estrutura do projeto
- `docker-compose.yml` define os serviços `app` e `mysql`.
- O código da aplicação fica dentro da pasta `src/`.

## Licença
Distribuído sob a licença MIT.
