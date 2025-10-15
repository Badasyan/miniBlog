#!/bin/bash
set -e

echo "🔧 Настройка MiniBlog..."

# Создаем необходимые директории если их нет
mkdir -p /var/www/storage/logs
mkdir -p /var/www/storage/framework/cache
mkdir -p /var/www/storage/framework/sessions
mkdir -p /var/www/storage/framework/views
mkdir -p /var/www/bootstrap/cache

# Создаем файл логов если его нет
touch /var/www/storage/logs/laravel.log

# Устанавливаем правильные права доступа
chown -R www-data:www-data /var/www/storage
chown -R www-data:www-data /var/www/bootstrap/cache
chmod -R 775 /var/www/storage
chmod -R 775 /var/www/bootstrap/cache

# Исправляем права на .env файл если он существует
if [ -f /var/www/.env ]; then
    chown www-data:www-data /var/www/.env
    chmod 644 /var/www/.env
fi

# Устанавливаем зависимости Composer если vendor не существует или пуст
if [ ! -d "/var/www/vendor" ] || [ -z "$(ls -A /var/www/vendor 2>/dev/null)" ]; then
    echo "📦 Установка зависимостей Composer..."
    composer install --optimize-autoloader --no-interaction
    echo "✅ Зависимости установлены"
fi

# Генерируем ключ приложения если его нет
if [ -f /var/www/.env ] && ! grep -q "APP_KEY=base64:" /var/www/.env; then
    echo "🔑 Генерация ключа приложения..."
    php artisan key:generate --force
    echo "✅ Ключ приложения сгенерирован"
fi

echo "✅ Настройка завершена"

# Запускаем переданную команду
exec "$@"