# Imagen base con Apache y PHP
FROM php:8.2-apache

# Instala dependencias necesarias para PostgreSQL y PHP
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libssl-dev \
    unzip \
    zip \
    && docker-php-ext-install pdo pdo_pgsql

# Copia el contenido del proyecto
COPY . /var/www/html/

# Da permisos
RUN chown -R www-data:www-data /var/www/html/

# Habilita mod_rewrite (opcional)
RUN a2enmod rewrite

# Puerto
EXPOSE 80
