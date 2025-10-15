<?php

namespace App\Actions\Post;

use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class GetUserPostsAction
{
    public function __construct(
        private readonly PostRepositoryInterface $postRepository
    ) {}

    public function getAllByUserId(int $userId): Collection
    {
        return $this->postRepository->getByUserId($userId);
    }

    public function getActiveByUserId(int $userId): Collection
    {
        return $this->postRepository->getActiveByUserId($userId);
    }
}
