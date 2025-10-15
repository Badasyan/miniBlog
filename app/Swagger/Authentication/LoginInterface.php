<?php

namespace App\Swagger\Authentication;

use App\Http\Requests\Auth\LoginRequest;
use App\Actions\Auth\LoginAction;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

interface LoginInterface
{
    /**
     * @OA\Post(
     *     operationId="login",
     *     path="/login",
     *     summary="Авторизация пользователя",
     *     description="Авторизует пользователя и возвращает токен доступа",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешная авторизация. Используйте полученный токен для авторизации в Swagger UI",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="token",
     *                 type="string",
     *                 example="1|abc123def456...",
     *                 description="Bearer токен для авторизации. Скопируйте этот токен и используйте в кнопке 'Authorize'"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Неверные учетные данные",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     */
    public function login(LoginRequest $request, LoginAction $action): JsonResponse;
}
