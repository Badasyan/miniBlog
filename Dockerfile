FROM php:8.2-fpm

# 1️⃣ Устанавливаем системные зависимости
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libonig-dev libxml2-dev libzip-dev libicu-dev \
    libsqlite3-dev sqlite3 \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd intl zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2️⃣ Устанавливаем Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 3️⃣ Настраиваем рабочую директорию
WORKDIR /var/www

# 4️⃣ Создаем entrypoint скрипт для автоматической настройки
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# 5️⃣ Создаем необходимые директории
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]