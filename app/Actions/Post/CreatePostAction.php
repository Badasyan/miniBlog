<?php

namespace App\Actions\Post;

use App\DTOs\Post\CreatePostDTO;
use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;

class CreatePostAction
{
    public function __construct(
        private readonly PostRepositoryInterface $postRepository
    ) {}

    public function execute(CreatePostDTO $dto): Post
    {
        return $this->postRepository->create($dto->toArray());
    }
}
