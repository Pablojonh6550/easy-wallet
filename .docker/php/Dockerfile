FROM php:8.3-fpm

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Define diretório de trabalho
WORKDIR /var/www

# Copia arquivos do projeto
COPY . .

# Permissões
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

# Copia o script de entrada
COPY .docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Define o script de entrada
ENTRYPOINT ["entrypoint.sh"]

# Exposição da porta para o PHP-FPM (default: 9000)
EXPOSE 9000
