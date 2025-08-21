# ===== 1. Imagen base PHP con Apache y SQL Server drivers =====
FROM mcr.microsoft.com/php/php:8.2-apache-sqlsrv

# ===== 2. Instalar dependencias del sistema =====
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    libssl-dev \
    libsqlite3-dev \
    gnupg2 \
    unixodbc-dev \
    apt-transport-https \
    libsodium-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd sodium

# ===== 3. Instalar Composer =====
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ===== 4. Copiar proyecto Laravel =====
WORKDIR /var/www/html
COPY . /var/www/html

# ===== 5. Instalar dependencias PHP de Laravel =====
RUN composer install --no-dev --optimize-autoloader

# ===== 6. Configurar permisos =====
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# ===== 7. Configuración de Apache =====
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN sed -i 's/80/8080/' /etc/apache2/ports.conf
RUN sed -i 's/:80/:8080/' /etc/apache2/sites-available/000-default.conf

# ===== 8. Limpiar caches de Laravel =====
RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan cache:clear \
    && php artisan view:clear

# ===== 9. Exponer puerto =====
EXPOSE 8080

# ===== 10. Comando por defecto =====
CMD ["apache2-foreground"]
