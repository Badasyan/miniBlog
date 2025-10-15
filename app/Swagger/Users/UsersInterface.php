<?php

namespace App\Swagger\Users;

use App\Actions\User\DeleteUserAction;
use App\Actions\User\UpdateUserAction;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

interface UsersInterface
{
    /**
     * @OA\Get(
     *     operationId="getCurrentUser",
     *     path="/user",
     *     summary="Получить профиль текущего пользователя",
     *     description="Возвращает информацию о текущем аутентифицированном пользователе включая ID, имя, email и даты создания/обновления",
     *     tags={"Users"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Профиль пользователя успешно получен",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/User",
     *                 description="Данные пользователя"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Пользователь не авторизован. Требуется Bearer token",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function show(Request $request): UserResource;

    /**
     * @OA\Put(
     *     operationId="updateCurrentUser",
     *     path="/user",
     *     summary="Обновить профиль пользователя",
     *     description="Обновляет информацию о текущем пользователе",
     *     tags={"Users"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Данные для обновления профиля пользователя",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 maxLength=255,
     *                 description="Имя пользователя",
     *                 example="John Doe"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 description="Email адрес пользователя",
     *                 example="john@example.com"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Профиль успешно обновлен",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Не авторизован",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибки валидации",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     */
    public function update(UpdateUserRequest $request, UpdateUserAction $action): UserResource;

    /**
     * @OA\Delete(
     *     operationId="deleteCurrentUser",
     *     path="/user",
     *     summary="Удалить аккаунт пользователя",
     *     description="Удаляет аккаунт текущего пользователя и все связанные данные (посты, комментарии). Это действие необратимо.",
     *     tags={"Users"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=204,
     *         description="Аккаунт пользователя успешно удален. Все связанные данные также удалены."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Пользователь не авторизован. Требуется Bearer token",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function destroy(Request $request, DeleteUserAction $action): UserResource;
}
