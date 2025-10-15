<?php

namespace App\Actions\Post;

use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Support\Facades\DB;

class DeletePostAction
{
    public function __construct(
        private readonly PostRepositoryInterface $postRepository
    ) {}

    public function execute(Post $post): bool
    {
        return $this->postRepository->delete($post);
    }
}
