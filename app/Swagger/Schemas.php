<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="User",
 *     title="User",
 *     description="Пользователь системы",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Уникальный идентификатор пользователя",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Имя пользователя",
 *         maxLength=255,
 *         example="John Doe"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         description="Email адрес пользователя",
 *         example="john@example.com"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Дата и время создания аккаунта",
 *         example="2023-01-01T00:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Дата и время последнего обновления",
 *         example="2023-01-01T00:00:00Z"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="Post",
 *     title="Post",
 *     description="Пост пользователя",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="body", type="string", example="Содержание поста"),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
 *     @OA\Property(property="user", ref="#/components/schemas/User"),
 *     @OA\Property(
 *         property="comments",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Comment")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="Comment",
 *     title="Comment",
 *     description="Комментарий к посту или другому комментарию",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="body", type="string", example="Текст комментария"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
 *     @OA\Property(property="user", ref="#/components/schemas/User"),
 *     @OA\Property(
 *         property="commentable",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="type", type="string", example="Post")
 *     ),
 *     @OA\Property(property="replies_count", type="integer", example=5),
 *     @OA\Property(
 *         property="replies",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Comment")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="PaginationMeta",
 *     title="Pagination Meta",
 *     description="Метаданные пагинации",
 *     @OA\Property(property="current_page", type="integer", example=1),
 *     @OA\Property(property="from", type="integer", example=1),
 *     @OA\Property(property="last_page", type="integer", example=10),
 *     @OA\Property(property="per_page", type="integer", example=15),
 *     @OA\Property(property="to", type="integer", example=15),
 *     @OA\Property(property="total", type="integer", example=150)
 * )
 *
 * @OA\Schema(
 *     schema="PaginationLinks",
 *     title="Pagination Links",
 *     description="Ссылки пагинации",
 *     @OA\Property(property="first", type="string", example="http://example.com/api/posts?page=1"),
 *     @OA\Property(property="last", type="string", example="http://example.com/api/posts?page=10"),
 *     @OA\Property(property="prev", type="string", example=null, nullable=true),
 *     @OA\Property(property="next", type="string", example="http://example.com/api/posts?page=2")
 * )
 *
 * @OA\Schema(
 *     schema="ValidationError",
 *     title="Validation Error",
 *     description="Ошибка валидации",
 *     @OA\Property(property="message", type="string", example="The given data was invalid."),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         example={
 *             "email": {"The email field is required."},
 *             "password": {"The password field is required."}
 *         }
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     title="Error Response",
 *     description="Стандартный ответ с ошибкой",
 *     @OA\Property(property="message", type="string", example="Error message")
 * )
 */
class Schemas
{
    // Этот класс содержит только схемы OpenAPI
}
