#!/bin/bash

# Caminho do projeto Laravel
APP_DIR="/var/www"

cd $APP_DIR

# Copia o .env.example se .env não existir
if [ ! -f .env ]; then
    echo "Copiando .env.example para .env"
    cp .env.example .env
fi

# Corrige permissões sempre que o container iniciar
echo "Corrigindo permissões..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Substitui as variáveis de banco no .env
echo "Configurando variáveis de ambiente"
sed -i "s/DB_HOST=.*/DB_HOST=${DB_HOST}/" .env
sed -i "s/DB_PORT=.*/DB_PORT=${DB_PORT}/" .env
sed -i "s/DB_DATABASE=.*/DB_DATABASE=${DB_DATABASE}/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=${DB_USERNAME}/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=${DB_PASSWORD}/" .env

# Instala dependências
composer install --no-interaction

# Gera a chave do app
php artisan key:generate

# Roda as migrations
php artisan migrate --seed

# Executa o processo padrão do container (php-fpm)
exec php-fpm
