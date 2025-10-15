<?php

namespace App\Swagger\Authentication;

use App\Http\Requests\Auth\RegisterRequest;
use App\Actions\Auth\RegisterAction;
use App\Http\Resources\UserResource;
use OpenApi\Annotations as OA;

interface RegisterInterface
{
    /**
     * @OA\Post(
     *     operationId="register",
     *     path="/register",
     *     summary="Регистрация нового пользователя",
     *     description="Создает нового пользователя в системе",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation"},
     *             @OA\Property(property="name", type="string", maxLength=255, example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", minLength=8, example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Пользователь успешно зарегистрирован",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибки валидации",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     */

    public function register(RegisterRequest $request, RegisterAction $action): UserResource;
}
