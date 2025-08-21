# ===== 1. Imagen base PHP con Apache =====
FROM php:8.2-apache

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

# ===== 3. Instalar SQL Server drivers =====
RUN curl -sSL https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl -sSL https://packages.microsoft.com/config/debian/11/prod.list > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update && ACCEPT_EULA=Y apt-get install -y msodbcsql17 \
    && pecl install sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

# ===== 4. Instalar Composer =====
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ===== 5. Copiar proyecto =====
WORKDIR /var/www/html
COPY . /var/www/html

# ===== 6. Instalar dependencias PHP de Laravel =====
RUN composer install --no-dev --optimize-autoloader

# ===== 7. Configurar permisos =====
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# ===== 8. Exponer puerto =====
EXPOSE 8080

# ===== 9. Comando por defecto =====
CMD ["apache2-foreground"]
