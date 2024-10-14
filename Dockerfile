# Usa la imagen oficial de PHP
FROM php:8.1-fpm

# Instala las dependencias necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql sockets

# Establece el directorio de trabajo
WORKDIR /var/www

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copia los archivos del proyecto
COPY . .

# Ejecuta composer install para instalar las dependencias de Laravel
RUN composer install

# Establece el comando por defecto para ejecutar el contenedor
CMD ["php-fpm"]
