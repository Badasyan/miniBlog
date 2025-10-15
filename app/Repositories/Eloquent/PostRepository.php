<?php

namespace App\Repositories\Eloquent;

use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PostRepository implements PostRepositoryInterface
{
    public function create(array $data): Post
    {
        return Post::create($data);
    }

    public function findById(int $id): ?Post
    {
        return Post::with(['user', 'comments'])->find($id);
    }

    public function update(Post $post, array $data): Post
    {
        $post->update($data);
        return $post->fresh(['user', 'comments']);
    }

    public function delete(Post $post): bool
    {
        return $post->delete();
    }

    public function getActiveByUserId(int $userId): Collection
    {
        return Post::with('user')
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getByUserId(int $userId): Collection
    {
        return Post::with('user')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Post::with(['user'])
            ->withCount('comments');

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['created_at'])) {
            $query->whereDate('created_at', $filters['created_at']);
        }

        $sortField = $filters['sort'] ?? 'created_at';
        $sortOrder = $filters['order'] ?? 'desc';
        $query->orderBy($sortField, $sortOrder);

        return $query->paginate($perPage);
    }
}
