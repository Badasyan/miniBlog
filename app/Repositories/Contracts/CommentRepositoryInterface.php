<?php

namespace App\Repositories\Contracts;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CommentRepositoryInterface
{
    public function create(array $data): Comment;

    public function findById(int $id): ?Comment;

    public function update(Comment $comment, array $data): Comment;

    public function delete(Comment $comment): bool;

    public function getByPostId(int $postId): Collection;

    public function getRepliesByCommentId(int $commentId): Collection;

    public function getByUserId(int $userId): Collection;

    public function getUserCommentsOnActivePosts(int $userId): Collection;

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;
}
