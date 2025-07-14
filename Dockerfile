FROM php:8.1-apache

# Instalar extensiones PHP necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Configurar Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Crear archivo .htaccess para reescritura de URLs
RUN echo 'RewriteEngine On' > /var/www/html/.htaccess && \
    echo 'RewriteRule ^controllers/(.*)$ /controllers/$1 [L]' >> /var/www/html/.htaccess

# Configurar el DocumentRoot para apuntar a public
ENV APACHE_DOCUMENT_ROOT /var/www/html

# Cambiar la configuración de Apache para el directorio correcto
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}/!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Crear directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . /var/www/html/

# Dar permisos necesarios
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html
