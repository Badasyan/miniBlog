<?php

namespace App\Swagger\Authentication;

use App\Actions\Auth\LogoutAction;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

interface LogoutInterface
{
    /**
     * @OA\Post(
     *     operationId="logout",
     *     path="/logout",
     *     summary="Выход из системы",
     *     description="Отзывает токен доступа пользователя",
     *     tags={"Authentication"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Успешный выход",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Не авторизован",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function logout(Request $request, LogoutAction $action): UserResource;
}
