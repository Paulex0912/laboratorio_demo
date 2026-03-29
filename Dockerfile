FROM php:8.2-apache

# 1. Instalar solo lo esencial para Postgres y GD
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    libpng-dev \
    libjpeg-dev \
    && docker-php-ext-install pdo pdo_pgsql gd

# 2. Configuración de Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

# 3. Preparar archivos (Ya incluyen el diseño que compilaste)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . .

# 4. Instalar PHP ignorando requisitos de plataforma
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# 5. Permisos de carpetas
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# 6. Inicio con limpieza de caché y migraciones
CMD php artisan config:clear && php artisan migrate --force && apache2-foreground

# ... después del COPY . .
RUN chown -R www-data:www-data /var/www/html/public