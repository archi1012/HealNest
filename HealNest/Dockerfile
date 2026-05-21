FROM php:8.2-cli

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libssl-dev \
    zip \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN npm install
RUN npm run build

EXPOSE 10000

CMD sh -c "php artisan key:generate --force --no-interaction || true && php artisan migrate --force || true && php artisan serve --host 0.0.0.0 --port ${PORT:-10000}"
