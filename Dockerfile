# ===== 1. Imagen base PHP con Apache =====
FROM php:8.2-apache

# ===== 2. Instalar dependencias del sistema =====
RUN apt-get update && apt-get install -y \
    apt-transport-https \
    gnupg2 \
    unixodbc \
    unixodbc-dev \
    libgssapi-krb5-2 \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip unzip curl \
    libssl-dev \
    libsqlite3-dev \
    libsodium-dev \
    && docker-php-ext-install pdo mbstring exif pcntl bcmath gd sodium

# ===== 3. Instalar Microsoft ODBC y drivers SQL Server =====
RUN curl https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor > microsoft.gpg \
    && mv microsoft.gpg /etc/apt/trusted.gpg.d/ \
    && curl https://packages.microsoft.com/config/debian/11/prod.list > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql18 \
    && pecl install sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

# ===== 4. Instalar Composer =====
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ===== 5. Copiar proyecto =====
WORKDIR /var/www/html
COPY . /var/www/html

# ===== 6. Instalar dependencias PHP de Laravel =====
RUN composer install --no-dev --optimize-autoloader

# ===== 7. Configurar permisos =====
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# ===== 8. Configuración de Apache =====
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN sed -i 's/80/8080/' /etc/apache2/ports.conf
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's/:80/:8080/' /etc/apache2/sites-available/000-default.conf

# ===== 9. Habilitar mod_rewrite y .htaccess =====
RUN a2enmod rewrite \
    && sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# ===== 10. Exponer puerto =====
EXPOSE 8080

# ===== 11. Comando por defecto =====
CMD php artisan config:clear && \
    php artisan route:clear && \
    php artisan cache:clear && \
    php artisan view:clear && \
    apache2-foreground
