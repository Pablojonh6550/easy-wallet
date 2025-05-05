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

-   Lembre-se de carregar as informações da base de dados dentro da .env!

```conf
Campos necessários:
    MYSQL_DATABASE: database-example
    MYSQL_ROOT_PASSWORD: password-example
    MYSQL_USER: user-example
    MYSQL_PASSWORD: password-example
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

Este projeto utiliza o sistema de autenticação padrão do Laravel baseado em sessões (Web Guard).

-   Formulário de login: `GET /`
-   Submissão do login: `POST /`
-   Formulário de registro: `GET /register`
-   Registro de novo usuário: `POST /register`
-   Logout: `GET /logout`

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

## 📄 Estrutura do Banco de Dados

-   `users`

| Coluna            | Tipo      | Descrição                       |
| ----------------- | --------- | ------------------------------- |
| id                | bigint    | ID único do usuário             |
| name              | string    | Nome do usuário                 |
| email             | string    | E-mail do usuário (único)       |
| email_verified_at | timestamp | Data de verificação do e-mail   |
| password          | string    | Senha criptografada             |
| remember_token    | string    | Token de sessão                 |
| created_at        | timestamp | Data de criação do registro     |
| updated_at        | timestamp | Data de atualização do registro |

-   `data_banks`

| Coluna          | Tipo      | Descrição                         |
| --------------- | --------- | --------------------------------- |
| id              | bigint    | ID único da conta bancária        |
| number_account  | string    | Número da conta                   |
| balance         | decimal   | Saldo atual                       |
| balance_special | decimal   | Limite especial da conta          |
| user_id         | bigint    | ID do usuário (chave estrangeira) |
| created_at      | timestamp | Data de criação do registro       |
| updated_at      | timestamp | Data de atualização do registro   |

-   `transactions`

| Coluna           | Tipo      | Descrição                                            |
| ---------------- | --------- | ---------------------------------------------------- |
| id               | bigint    | ID único da transação                                |
| user_id          | bigint    | ID do usuário que iniciou a transação                |
| data_bank_id     | bigint    | ID da conta bancária associada                       |
| amount           | decimal   | Valor da transação                                   |
| type             | string    | Tipo da transação: `deposit`, `transfer`, `reversal` |
| user_id_receiver | bigint    | (opcional) ID do usuário que recebeu a transferência |
| created_at       | timestamp | Data e hora de criação da transação                  |
| updated_at       | timestamp | Data e hora da última atualização da transação       |

## 🐞 Logs, Erros e Debug

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
