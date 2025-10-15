<?php

namespace App\Swagger\Posts;

use App\Actions\Post\CreatePostAction;
use App\Actions\Post\DeletePostAction;
use App\Actions\Post\GetUserPostsAction;
use App\Actions\Post\UpdatePostAction;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Annotations as OA;

interface PostsInterface
{
    /**
     * @OA\Get(
     *     operationId="getPosts",
     *     path="/posts",
     *     summary="Получить список постов",
     *     description="Возвращает пагинированный список всех постов с возможностью фильтрации",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="is_active",
     *         in="query",
     *         description="Фильтр по активности (true/false)",
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="Фильтр по ID пользователя",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="created_at",
     *         in="query",
     *         description="Фильтр по дате создания (Y-m-d)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Поле для сортировки",
     *         @OA\Schema(type="string", enum={"created_at", "updated_at"})
     *     ),
     *     @OA\Parameter(
     *         name="order",
     *         in="query",
     *         description="Направление сортировки",
     *         @OA\Schema(type="string", enum={"asc", "desc"})
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Количество элементов на странице (макс. 100)",
     *         @OA\Schema(type="integer", maximum=100)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список постов",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Post")),
     *             @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"),
     *             @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta")
     *         )
     *     )
     * )
     */
    public function index(Request $request): ResourceCollection;

    /**
     * @OA\Post(
     *     operationId="createPost",
     *     path="/posts",
     *     summary="Создать новый пост",
     *     description="Создает новый пост для аутентифицированного пользователя",
     *     tags={"Posts"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"body"},
     *             @OA\Property(property="body", type="string", maxLength=5000, example="Содержание поста")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Пост успешно создан",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/Post")
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
    public function store(CreatePostRequest $request, CreatePostAction $action);

    /**
     * @OA\Get(
     *     operationId="getPost",
     *     path="/posts/{id}",
     *     summary="Получить пост по ID",
     *     description="Возвращает детальную информацию о посте",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID поста",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Информация о посте",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/Post")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Пост не найден",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Доступ запрещен (неактивный пост)",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function show(Post $post): PostResource;

    /**
     * @OA\Put(
     *     operationId="updatePost",
     *     path="/posts/{id}",
     *     summary="Обновить пост",
     *     description="Обновляет пост (только владелец)",
     *     tags={"Posts"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID поста",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="body", type="string", maxLength=5000, example="Обновленное содержание поста"),
     *             @OA\Property(property="is_active", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Пост успешно обновлен",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/Post")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Не авторизован",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Доступ запрещен",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Пост не найден",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибки валидации",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     */
    public function update(UpdatePostRequest $request, Post $post, UpdatePostAction $action): PostResource;

    /**
     * @OA\Delete(
     *     operationId="deletePost",
     *     path="/posts/{id}",
     *     summary="Удалить пост",
     *     description="Удаляет пост (только владелец). Каскадно удаляет все комментарии",
     *     tags={"Posts"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID поста",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Пост успешно удален"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Не авторизован",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Доступ запрещен",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Пост не найден",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function destroy(Post $post, DeletePostAction $action): PostResource;

    /**
     * @OA\Get(
     *     operationId="getUserPosts",
     *     path="/users/{id}/posts",
     *     summary="Получить все посты пользователя",
     *     description="Возвращает все посты пользователя (активные и неактивные)",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID пользователя",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Посты пользователя",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Post"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Пользователь не найден",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function userPosts(User $user, GetUserPostsAction $action): ResourceCollection;

    /**
     * @OA\Get(
     *     operationId="getUserActivePosts",
     *     path="/users/{id}/posts/active",
     *     summary="Получить активные посты пользователя",
     *     description="Возвращает только активные посты пользователя",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID пользователя",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Активные посты пользователя",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Post"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Пользователь не найден",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function userActivePosts(User $user, GetUserPostsAction $action): ResourceCollection;

    /**
     * @OA\Get(
     *     operationId="getMyPosts",
     *     path="/my/posts",
     *     summary="Получить мои посты",
     *     description="Возвращает все посты текущего пользователя",
     *     tags={"Posts"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Мои посты",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Post"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Не авторизован",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function myPosts(Request $request, GetUserPostsAction $action): ResourceCollection;
}
