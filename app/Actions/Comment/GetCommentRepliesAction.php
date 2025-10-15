<?php

namespace App\Actions\Comment;

use App\Repositories\Contracts\CommentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class GetCommentRepliesAction
{
    public function __construct(
        private readonly CommentRepositoryInterface $commentRepository
    ) {}

    public function execute(int $commentId): Collection
    {
        return $this->commentRepository->getRepliesByCommentId($commentId);
    }
}
