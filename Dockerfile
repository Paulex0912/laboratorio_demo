FROM php:8.2-apache

# Instalar dependencias del sistema y conector Postgres
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql

# Configurar el sitio de Apache para Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Habilitar el módulo rewrite de Apache
RUN a2enmod rewrite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar el proyecto
WORKDIR /var/www/html
COPY . .

# Instalar dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# Dar permisos a storage y cache
RUN chown -R www-data:www-data storage bootstrap/cache