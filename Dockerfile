FROM php:8.2-apache

# Instalar dependencias del sistema, conector Postgres y librería de imágenes (GD)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd

# Configurar Apache para Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar el proyecto
WORKDIR /var/www/html
COPY . .

# Instalar dependencias ignorando restricciones de plataforma (Esto evita el error del log)
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Dar permisos
RUN chown -R www-data:www-data storage bootstrap/cache

# Comando de inicio con migraciones automáticas
CMD php artisan config:clear && php artisan migrate --force && apache2-foreground