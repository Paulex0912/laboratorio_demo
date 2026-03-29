FROM richarvey/php-apache-heroku:latest

# Copiar el código al servidor
COPY . /var/www/html

# Configurar variables de entorno básicas
ENV WEBROOT /var/www/html/public
ENV APP_ENV production

# Instalar dependencias de PHP y Node
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# Dar permisos a las carpetas de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache