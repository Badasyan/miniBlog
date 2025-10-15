<?php

namespace App\Actions\Comment;

use App\DTOs\Comment\CreateCommentDTO;
use App\Models\Comment;
use App\Repositories\Contracts\CommentRepositoryInterface;

class CreateCommentAction
{
    public function __construct(
        private readonly CommentRepositoryInterface $commentRepository
    ) {}

    public function execute(CreateCommentDTO $dto): Comment
    {
        return $this->commentRepository->create($dto->toArray());
    }
}
