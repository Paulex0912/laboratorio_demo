FROM php:8.2-apache

# 1. Dependencias esenciales
RUN apt-get update && apt-get install -y \
    libpq-dev unzip libpng-dev libjpeg-dev \
    && docker-php-ext-install pdo pdo_pgsql gd

# 2. Apache config
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

# 3. Copiar archivos (incluyendo el build que acabas de forzar)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . .

# 4. Instalar PHP
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# 5. Permisos (Vital para que se vea el diseño)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache public/build

# 6. Inicio
CMD php artisan config:clear && php artisan view:clear && php artisan migrate --force && apache2-foreground

# ... después de COPY . .
RUN chmod -R 775 public/build
RUN chown -R www-data:www-data /var/www/html/public