<?php

namespace App\Repositories\Contracts;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface PostRepositoryInterface
{
    public function create(array $data): Post;

    public function findById(int $id): ?Post;

    public function update(Post $post, array $data): Post;

    public function delete(Post $post): bool;

    public function getActiveByUserId(int $userId): Collection;

    public function getByUserId(int $userId): Collection;

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;
}
