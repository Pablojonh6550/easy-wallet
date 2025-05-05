# 📦 Easy-Wallet

Aplicação desenvolvida com Laravel para o gerenciamento de finanças.

## 🚀 Tecnologias

-   Laravel (v12)
-   PHP (>=8.2)
-   MySQL
-   Composer
-   Docker
-   PHPUnit

## ⚙️ Requisitos

-   PHP >= 8.2
-   Composer
-   MySQL
-   Laravel CLI
-   Docker

## 🚧 Instalação

```conf
# Clone o repositório
git clone https://github.com/Pablojonh6550/easy-wallet.git
cd easy-wallet

# Copie o arquivo de ambiente
cp .env.example .env

# Suba os containers
docker compose up -d --build
```

## ▶️ Acessos

-   Aplicação (local): http://localhost:8000
-   Container PHP (CLI): docker-compose exec easy-wallet-app bash
-   Banco de dados: acessível pela porta configurada no .env (DB_PORT)

## 🧪 Testes

-   Localização: `tests/Unit/`

```conf
docker-compose exec easy-wallet-app php artisan test
# ou
docker-compose exec easy-wallet-app ./vendor/bin/phpunit

```

## 🔐 Autenticação

Este projeto utiliza o sistema de autenticação padrão do Laravel (session-based authentication).

-   Para fazer login: POST /login

```
Authorization: Bearer {token}
```

## 📌 Rotas Web

| Método | Rota            | Descrição                               |
| ------ | --------------- | --------------------------------------- |
| GET    | /               | Exibe o formulário de login             |
| POST   | /               | Submete o login do usuário              |
| GET    | /register       | Exibe o formulário de registro          |
| POST   | /register       | Submete os dados de registro do usuário |
| GET    | /dashboard      | Exibe o painel do usuário autenticado   |
| GET    | /logout         | Realiza logout do usuário               |
| GET    | /deposit        | Exibe o formulário de depósito          |
| POST   | /deposit/value  | Realiza uma operação de depósito        |
| GET    | /transfer       | Exibe o formulário de transferência     |
| POST   | /transfer/value | Realiza uma operação de transferência   |
| GET    | /history        | Exibe o extrato de transações           |
| POST   | /reverse        | Realiza a reversão de uma transação     |

## 🐞 Logs

Os erros são registrados em `storage/logs/laravel.log`.

Utilize `Log::error()` ou `report()` para registrar exceções.

## 🧰 Comandos Úteis

```conf
# Limpar caches
docker exec -it easy-wallet-app /bin/bash php artisan config:clear
docker exec -it easy-wallet-app php artisan route:clear
docker exec -it easy-wallet-app php artisan cache:clear

# Caso as chaves não sejam geradas
# Gerar a chave da aplicação
docker exec -it easy-wallet-app php artisan key:generate


# Caso o banco não seja populado
# Executar as migrações do banco de dados
docker exec -it easy-wallet-app php artisan migrate --seed
```

## 📄 Documentação

-   [Documentação da API - Lolcal](public/documentation/)

## 🧾 Licença

Este projeto está licenciado sob a MIT License.
