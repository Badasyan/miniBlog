<?php

namespace App\Swagger\Comments;

use App\Actions\Comment\CreateCommentAction;
use App\Actions\Comment\DeleteCommentAction;
use App\Actions\Comment\UpdateCommentAction;
use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Annotations as OA;

interface CommentsInterface
{
    /**
     * @OA\Get(
     *     operationId="getComments",
     *     path="/comments",
     *     summary="Получить список комментариев",
     *     description="Возвращает пагинированный список всех комментариев с возможностью фильтрации",
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="Фильтр по ID пользователя",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="commentable_type",
     *         in="query",
     *         description="Тип объекта комментария",
     *         @OA\Schema(type="string", enum={"post", "comment"})
     *     ),
     *     @OA\Parameter(
     *         name="commentable_id",
     *         in="query",
     *         description="ID объекта комментария",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Количество элементов на странице",
     *         @OA\Schema(type="integer", maximum=100)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список комментариев",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Comment")),
     *             @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"),
     *             @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta")
     *         )
     *     )
     * )
     */
    public function index(Request $request): ResourceCollection;

    /**
     * @OA\Post(
     *     operationId="createComment",
     *     path="/comments",
     *     summary="Создать комментарий",
     *     description="Создает новый комментарий к посту или другому комментарию",
     *     tags={"Comments"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"body"},
     *             @OA\Property(property="body", type="string", maxLength=5000, example="Текст комментария"),
     *             @OA\Property(property="commentable_type", type="string", enum={"post", "comment"}, example="post"),
     *             @OA\Property(property="commentable_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Комментарий успешно создан",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/Comment")
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
    public function store(CreateCommentRequest $request, CreateCommentAction $action): CommentResource;

    /**
     * @OA\Get(
     *     operationId="getComment",
     *     path="/comments/{id}",
     *     summary="Получить комментарий по ID",
     *     description="Возвращает детальную информацию о комментарии с ответами",
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID комментария",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Информация о комментарии",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/Comment")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Комментарий не найден",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function show(Comment $comment): CommentResource;

    /**
     * @OA\Put(
     *     operationId="updateComment",
     *     path="/comments/{id}",
     *     summary="Обновить комментарий",
     *     description="Обновляет комментарий (только владелец)",
     *     tags={"Comments"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID комментария",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"body"},
     *             @OA\Property(property="body", type="string", maxLength=5000, example="Обновленный текст комментария")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Комментарий успешно обновлен",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/Comment")
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
     *         description="Комментарий не найден",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибки валидации",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     */
    public function update(UpdateCommentRequest $request, Comment $comment, UpdateCommentAction $action): CommentResource;

    /**
     * @OA\Delete(
     *     operationId="deleteComment",
     *     path="/comments/{id}",
     *     summary="Удалить комментарий",
     *     description="Удаляет комментарий (только владелец). Каскадно удаляет все ответы",
     *     tags={"Comments"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID комментария",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Комментарий успешно удален"
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
     *         description="Комментарий не найден",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function destroy(Comment $comment, DeleteCommentAction $action): CommentResource;

    /**
     * @OA\Get(
     *     operationId="getPostComments",
     *     path="/posts/{id}/comments",
     *     summary="Получить комментарии к посту",
     *     description="Возвращает все комментарии к указанному посту",
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID поста",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Комментарии к посту",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Comment"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Пост не найден",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function postComments(Post $post): ResourceCollection;

    /**
     * @OA\Post(
     *     operationId="createPostComment",
     *     path="/posts/{id}/comments",
     *     summary="Создать комментарий к посту",
     *     description="Создает новый комментарий к указанному посту",
     *     tags={"Comments"},
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
     *             required={"body"},
     *             @OA\Property(property="body", type="string", maxLength=5000, example="Текст комментария")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Комментарий успешно создан",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/Comment")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Не авторизован",
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
    public function storeForPost(CreateCommentRequest $request, Post $post, CreateCommentAction $action): CommentResource;

    /**
     * @OA\Get(
     *     operationId="getCommentReplies",
     *     path="/comments/{id}/replies",
     *     summary="Получить ответы на комментарий",
     *     description="Возвращает все ответы на указанный комментарий",
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID комментария",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ответы на комментарий",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Comment"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Комментарий не найден",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function commentReplies(Comment $comment): ResourceCollection;

    /**
     * @OA\Post(
     *     operationId="createCommentReply",
     *     path="/comments/{id}/replies",
     *     summary="Создать ответ на комментарий",
     *     description="Создает новый ответ на указанный комментарий",
     *     tags={"Comments"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID комментария",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"body"},
     *             @OA\Property(property="body", type="string", maxLength=5000, example="Текст ответа")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Ответ успешно создан",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/Comment")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Не авторизован",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Комментарий не найден",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибки валидации",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     */
    public function storeReply(CreateCommentRequest $request, Comment $comment, CreateCommentAction $action): CommentResource;

    /**
     * @OA\Get(
     *     operationId="getUserComments",
     *     path="/users/{id}/comments",
     *     summary="Получить комментарии пользователя",
     *     description="Возвращает все комментарии указанного пользователя",
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID пользователя",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Комментарии пользователя",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Comment"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Пользователь не найден",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function userComments(User $user): ResourceCollection;

    /**
     * @OA\Get(
     *     operationId="getMyComments",
     *     path="/my/comments",
     *     summary="Получить мои комментарии",
     *     description="Возвращает все комментарии текущего пользователя",
     *     tags={"Comments"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Мои комментарии",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Comment"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Не авторизован",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function myComments(Request $request): ResourceCollection;
}
