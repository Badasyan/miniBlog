<?php

namespace App\Actions\Comment;

use App\DTOs\Comment\UpdateCommentDTO;
use App\Models\Comment;
use App\Repositories\Contracts\CommentRepositoryInterface;

class UpdateCommentAction
{
    public function __construct(
        private readonly CommentRepositoryInterface $commentRepository
    ) {}

    public function execute(Comment $comment, UpdateCommentDTO $dto): Comment
    {
        return $this->commentRepository->update($comment, $dto->toArray());
    }
}
