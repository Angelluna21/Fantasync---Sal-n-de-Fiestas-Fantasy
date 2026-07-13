FROM serversideup/php:8.3-web

# Configuramos la carpeta "public" como la raíz de nuestro servidor web (Nginx)
ENV WEB_DOCUMENT_ROOT=/var/www/html/public

# Cambiamos temporalmente al usuario root para instalar los paquetes necesarios
USER root

# Instalamos la extensión de PostgreSQL (para poder conectar con Supabase)
RUN install-php-extensions pdo_pgsql pgsql

# Instalamos Node.js para poder compilar los assets de Vite
RUN apt-get update && apt-get install -y curl \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Regresamos al usuario www-data por seguridad
USER www-data

# Copiamos todo nuestro código fuente dentro del contenedor con los permisos correctos
COPY --chown=www-data:www-data . /var/www/html

# 1. Instalamos dependencias de PHP (sin las de desarrollo)
# 2. Instalamos paquetes de NPM
# 3. Compilamos nuestros CSS/JS con Vite (npm run build)
# 4. Borramos la carpeta node_modules para que nuestra imagen pese menos
RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && npm install \
    && npm run build \
    && rm -rf node_modules
