<?php

namespace App\Actions\Comment;

use App\Models\Comment;
use App\Repositories\Contracts\CommentRepositoryInterface;
use Illuminate\Support\Facades\DB;

class DeleteCommentAction
{
    public function __construct(
        private readonly CommentRepositoryInterface $commentRepository
    ) {}

    public function execute(Comment $comment): bool
    {
            return $this->commentRepository->delete($comment);
    }
}
