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

# 6. Directorio de trabajo y copia del código fuente
WORKDIR /var/www/html

# Importante: Copia todo el código fuente de Laravel AHORA.
# En Railway, este Dockerfile se ejecutará en la raíz de tu proyecto.
COPY . /var/www/html

# 7. Instalación de dependencias de Laravel y Build (Pasos del docker-compose.yml)
# Este es el paso crucial que prepara la aplicación para su ejecución.
RUN composer install --no-dev --prefer-dist --optimize-autoloader
RUN npm install
RUN npm run build

# 8. Permisos: Asegurar que Apache pueda escribir en storage y bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Exponer Puerto y Comando de Ejecución
# Railway inyectará una variable $PORT en lugar del 80 por defecto.
# El comando de inicio debe ser el que mantiene Apache ejecutándose en primer plano.
# NOTA: La migración (php artisan migrate) NO DEBE ir en el Dockerfile, sino en el script de inicio
# o en la configuración de Railway, después de que la DB esté disponible.
EXPOSE 80

# Comando final que inicia Apache
CMD ["apache2-foreground"]