<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="MiniBlog API",
 *     version="1.0.0",
 *     description="API для управления пользователями, постами и комментариями",
 *     @OA\Contact(
 *         email="admin@miniblog.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://127.0.0.1:8000/api",
 *     description="Development Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Laravel Sanctum token authentication"
 * )
 *
 * @OA\Tag(
 *     name="Authentication",
 *     description="Аутентификация пользователей"
 * )
 *
 * @OA\Tag(
 *     name="Users",
 *     description="Управление пользователями"
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
 */
class SwaggerController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}

