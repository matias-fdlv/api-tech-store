# 1. Etapa Base: Usamos una imagen que ya incluye PHP y Apache
FROM php:8.3-apache

# 2. Instalar dependencias del sistema y Node.js para Vite/Mix (Laravel Frontend)
# Usamos una sola instrucción RUN para minimizar el número de capas
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    curl \
    gnupg \
    && curl -fsSL https://deb.nodesource.com/setup_current.x | bash - \
    && apt-get install -y nodejs \
    # Limpiamos caché para reducir el tamaño de la imagen
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Instalar extensiones de PHP necesarias para Laravel
RUN docker-php-ext-install pdo pdo_mysql zip

# 4. Instalar Composer (copiándolo directamente desde su imagen oficial)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Configuración de Apache para Laravel (apuntando a /public)
RUN a2enmod rewrite
# Cambiar el directorio raíz de Apache a /var/www/html/public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 6. Directorio de trabajo
WORKDIR /var/www/html

# 7. COPIA DEL CÓDIGO FUENTE (¡CRÍTICO!)
# Ahora copiamos todos los archivos del proyecto al directorio de trabajo.
COPY . /var/www/html

# 8. Instalación de dependencias de Laravel y Build
# Ahora composer y npm pueden encontrar los archivos composer.json y package.json.
RUN composer install --no-dev --prefer-dist --optimize-autoloader
RUN npm install
RUN npm run build

# 9. Permisos (Asegurar que www-data pueda escribir en storage/cache)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 10. Exponer Puerto y Comando de Ejecución
EXPOSE 80

CMD ["apache2-foreground"]

