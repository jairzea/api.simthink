# Imagen base PHP con Apache
FROM php:8.2-apache

# Instalar dependencias del sistema y extensiones PHP requeridas por Laravel
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Habilitar mod_rewrite para Laravel
RUN a2enmod rewrite

# Copiar archivos del proyecto
COPY . /var/www/html

# Establecer permisos para Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Configuración personalizada de Apache para Laravel
# COPY ./docker/apache/laravel.conf /etc/apache2/sites-available/000-default.conf

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Instalar dependencias PHP de Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

EXPOSE 80
CMD ["apache2-foreground"]