# Usa la imagen oficial de PHP con Apache
FROM php:8.1-apache

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

# Habilita el módulo de reescritura de Apache
RUN a2enmod rewrite

# Establece el directorio de trabajo como la carpeta pública de Laravel
WORKDIR /var/www/html

# Copia los archivos del proyecto al directorio raíz de Apache
COPY . .

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Ejecuta composer install para instalar las dependencias de Laravel
RUN composer install

# Establece la configuración de Apache para apuntar al directorio 'public'
RUN echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n\
\n\
<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Establece el comando por defecto para ejecutar Apache
CMD ["apache2-foreground"]
