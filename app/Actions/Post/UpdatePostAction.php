<?php

namespace App\Actions\Post;

use App\DTOs\Post\UpdatePostDTO;
use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;

class UpdatePostAction
{
    public function __construct(
        private readonly PostRepositoryInterface $postRepository
    ) {}

    public function execute(Post $post, UpdatePostDTO $dto): Post
    {
        return $this->postRepository->update($post, $dto->toArray());
    }
}
