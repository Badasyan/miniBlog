<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="MiniBlog API",
 *      description="API для управления пользователями, постами и комментариями.
 *
 * ## Аутентификация
 *
 * Для доступа к защищенным эндпоинтам используйте Bearer токен:
 * 1. Зарегистрируйтесь через `/register` или войдите через `/login`
 * 2. Скопируйте полученный токен
 * 3. Нажмите кнопку 'Authorize' в верхней части страницы
 * 4. Введите токен в формате: `Bearer ваш_токен_здесь`
 * 5. Нажмите 'Authorize' и 'Close'
 *
 * После этого вы сможете использовать все защищенные эндпоинты.",
 *      @OA\Contact(
 *          email="admin@miniblog.com"
 *      )
 * )
 *
 * @OA\Server(
 *      url="http://127.0.0.1:8000/api",
 *      description="Development Server"
 * )
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="sanctum"
 * )
 *
 * @OA\Tag(
 *     name="Authentication",
 *     description="Аутентификация пользователей"
 * )
 *
 * @OA\Tag(
 *     name="Posts",
 *     description="Управление постами"
 * )
 *
 * @OA\Tag(
 *     name="Comments",
 *     description="Управление комментариями"
 * )
 *
 * @OA\Tag(
 *     name="Users",
 *     description="Управление пользователями"
 * )
 */
class OpenApiInfo
{
    // Этот класс содержит только аннотации OpenAPI
}
