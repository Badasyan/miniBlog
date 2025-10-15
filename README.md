# MiniBlog API

CRUD API для управления пользователями, постами и комментариями, построенный на Laravel 10 с использованием принципов SOLID и архитектурных паттернов.

## 🚀 Технологии

- **Backend**: Laravel 10, PHP 8.2
- **База данных**: MySQL 8.0
- **Аутентификация**: Laravel Sanctum
- **Контейнеризация**: Docker, Docker Compose
- **Веб-сервер**: Nginx
- **Тестирование**: PHPUnit (Feature + Unit тесты)
- **Документация**: Swagger/OpenAPI 3.0

## 🏗️ Архитектура

Проект следует принципам SOLID и использует следующие паттерны:

- **Repository Pattern** - для работы с данными
- **Action Pattern** - для бизнес-логики
- **DTO Pattern** - для передачи данных
- **FormRequest** - для валидации
- **Laravel Resources** - для форматирования API ответов
- **Laravel Policies** - для авторизации

## 📋 Функциональность

### Пользователи
- Регистрация и аутентификация
- Управление профилем
- Удаление аккаунта (каскадное удаление постов/комментариев)

### Посты
- CRUD операции
- Статус активности (`is_active`)
- Фильтрация и пагинация
- Авторизация на основе владения

### Комментарии
- Полиморфные связи (к постам и другим комментариям)
- Неограниченная вложенность
- Каскадное удаление ответов
- Фильтрация и пагинация

## 🛠️ Установка и запуск

### Предварительные требования
- Docker и Docker Compose
- Git

### 🚀 Быстрая установка

```bash
# 1. Клонирование репозитория
git clone <repository-url>
cd miniBlog
```

```bash
# 2. Создание .env файла
cp .env.example .env

# 3. Запуск Docker (все настраивается автоматически)
docker compose up -d --build

# 4. Ожидание запуска MySQL
sleep 15

# 5. Выполнение миграций
docker compose exec app php artisan migrate --force

# 6. Генерация Swagger документации
docker compose exec app php artisan l5-swagger:generate
```

**Готово!** Все сервисы настроены и работают. 

**Что происходит автоматически:**
- ✅ Установка зависимостей Composer
- ✅ Генерация ключа приложения
- ✅ Настройка прав доступа
- ✅ Создание необходимых папок

### 4. Доступ к приложению
- **API**: http://127.0.0.1:8000/api
- **Swagger документация**: http://127.0.0.1:8000/api/documentation
- **phpMyAdmin**: http://127.0.0.1:8080

## 🧪 Тестирование

### Запуск всех тестов
```bash

docker exec -t miniblog_app php artisan test
```

### Типы тестов
- **Feature тесты**: Тестирование API эндпоинтов
- **Unit тесты**: Тестирование валидации, репозиториев, действий

## 📚 API Документация

### Swagger UI
Доступна по адресу: http://127.0.0.1:8000/api/documentation

### Основные эндпоинты

#### Аутентификация
- `POST /api/register` - Регистрация
- `POST /api/login` - Авторизация
- `POST /api/logout` - Выход (требует токен)

#### Пользователи
- `GET /api/user` - Профиль текущего пользователя
- `PUT /api/user` - Обновление профиля
- `DELETE /api/user` - Удаление аккаунта

#### Посты
- `GET /api/posts` - Список постов (с фильтрами)
- `POST /api/posts` - Создание поста
- `GET /api/posts/{id}` - Получение поста
- `PUT /api/posts/{id}` - Обновление поста
- `DELETE /api/posts/{id}` - Удаление поста

#### Комментарии
- `GET /api/comments` - Список комментариев
- `POST /api/posts/{post}/comments` - Комментарий к посту
- `POST /api/comments/{comment}/replies` - Ответ на комментарий
- `PUT /api/comments/{id}` - Обновление комментария
- `DELETE /api/comments/{id}` - Удаление комментария

### Аутентификация
API использует Bearer токены (Laravel Sanctum):
```
Authorization: Bearer {your-token}
```

## 🔧 Разработка

### Структура проекта
```
app/
├── Actions/          # Бизнес-логика
├── DTOs/            # Объекты передачи данных
├── Http/
│   ├── Controllers/ # API контроллеры
│   ├── Requests/    # Валидация запросов
│   └── Resources/   # Форматирование ответов
├── Models/          # Eloquent модели
├── Policies/        # Авторизация
└── Repositories/    # Работа с данными
```

### Команды разработки
```bash
# Генерация Swagger документации
docker exec -t miniblog_app php artisan l5-swagger:generate

# Запуск миграций
docker exec -t miniblog_app php artisan migrate

# Откат миграций
docker exec -t miniblog_app php artisan migrate:rollback

# Очистка кеша
docker exec -t miniblog_app php artisan cache:clear
docker exec -t miniblog_app php artisan config:clear
```

## 🔧 Решение проблем

### ❌ Ошибка "Permission denied" для storage/logs/laravel.log

Если при открытии Swagger UI появляется ошибка с правами доступа:

```bash
# Исправление прав доступа
docker compose exec --user root app chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
docker compose exec --user root app chmod -R 775 /var/www/storage /var/www/bootstrap/cache
```

### ❌ Ошибка "vendor/autoload.php not found"

```bash
# Пересборка контейнеров
docker compose down
docker compose build --no-cache
docker compose up -d
```

### ❌ Ошибка подключения к базе данных

```bash
# Проверка статуса контейнеров
docker compose ps

# Перезапуск MySQL
docker compose restart mysql
sleep 20

# Повторное выполнение миграций
docker compose exec app php artisan migrate --force
```

### 🔄 Полная переустановка

Если ничего не помогает:

```bash
# Полная очистка
docker compose down -v
docker system prune -f

# Повторная установка
docker compose up -d --build
sleep 20
docker compose exec app php artisan key:generate --force
docker compose exec app php artisan migrate --force
docker compose exec app php artisan l5-swagger:generate
```

## 📊 База данных

### Схема
- **users**: Пользователи системы
- **posts**: Посты пользователей
- **comments**: Комментарии (полиморфные связи)
- **personal_access_tokens**: Токены Sanctum

### Связи
- User → Posts (1:N)
- User → Comments (1:N)
- Post → Comments (1:N, полиморфная)
- Comment → Replies (1:N, полиморфная, рекурсивная)

## 🐳 Docker

### Сервисы
- **app**: PHP 8.2-FPM приложение
- **nginx**: Веб-сервер
- **mysql**: База данных MySQL 8.0
- **phpmyadmin**: Веб-интерфейс для MySQL

### Порты
- **8000**: Nginx (API)
- **8080**: phpMyAdmin
- **3307**: MySQL (внешний доступ)

## 🔒 Безопасность

- Валидация всех входящих данных
- Авторизация на основе владения ресурсами
- Защита от SQL инъекций (Eloquent ORM)
- Rate limiting (Laravel)
- CORS настройки

## 📝 Лицензия

MIT License
