<?php

namespace App\Repositories\Eloquent;

use App\Models\Comment;
use App\Models\Post;
use App\Repositories\Contracts\CommentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CommentRepository implements CommentRepositoryInterface
{

    public function create(array $data): Comment
    {
        return Comment::create($data);
    }

    public function findById(int $id): ?Comment
    {
        return Comment::with(['user', 'commentable', 'replies'])->find($id);
    }

    public function update(Comment $comment, array $data): Comment
    {
        $comment->update($data);
        return $comment->fresh(['user', 'commentable', 'replies']);
    }

    public function delete(Comment $comment): bool
    {
        return $comment->delete();
    }

    public function getByPostId(int $postId): Collection
    {
        return Comment::with(['user', 'allReplies.user'])
            ->where('commentable_type', Post::class)
            ->where('commentable_id', $postId)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function getRepliesByCommentId(int $commentId): Collection
    {
        return Comment::with(['user', 'allReplies.user'])
            ->where('commentable_type', Comment::class)
            ->where('commentable_id', $commentId)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function getByUserId(int $userId): Collection
    {
        return Comment::with(['user', 'commentable'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getUserCommentsOnActivePosts(int $userId): Collection
    {
        return Comment::with(['user', 'commentable'])
            ->where('user_id', $userId)
            ->where(function ($query) {
                $query->where('commentable_type', Post::class)
                    ->whereHas('commentable', function ($postQuery) {
                        $postQuery->where('is_active', true);
                    })
                    ->orWhere('commentable_type', Comment::class);
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Comment::with(['user', 'commentable'])
            ->withCount('replies');

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['commentable_type'])) {
            $query->where('commentable_type', $filters['commentable_type']);
        }

        if (isset($filters['commentable_id'])) {
            $query->where('commentable_id', $filters['commentable_id']);
        }

        if (isset($filters['created_at'])) {
            $query->whereDate('created_at', $filters['created_at']);
        }

        $sortField = $filters['sort'] ?? 'created_at';
        $sortOrder = $filters['order'] ?? 'asc';
        $query->orderBy($sortField, $sortOrder);

        return $query->paginate($perPage);
    }
}
