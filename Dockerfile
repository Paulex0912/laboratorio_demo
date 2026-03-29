FROM php:8.2-apache

# 1. Instalar dependencias del sistema y librerías de imagen
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd

# 2. Configurar Apache para Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite

# 3. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Copiar el proyecto y preparar entorno
WORKDIR /var/www/html
COPY . .

# 5. Instalar dependencias de PHP y Node (Vite)
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs
RUN npm install && npm run build

# 6. Permisos críticos para evitar el Error 500
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 7. Comando de inicio (Limpia caché y lanza servidor)
CMD php artisan config:clear && php artisan view:clear && php artisan migrate --force && apache2-foreground