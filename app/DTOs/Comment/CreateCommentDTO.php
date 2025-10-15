<?php

namespace App\DTOs\Comment;

use App\Http\Requests\Comment\CreateCommentRequest;
use App\Models\Comment;
use App\Models\Post;

class CreateCommentDTO
{
    public function __construct(
        public readonly string $body,
        public readonly int $commentableId,
        public readonly string $commentableType,
        public readonly int $userId
    ) {}

    public static function fromRequest(CreateCommentRequest $request): self
    {
        $type = $request->validated('commentable_type');

        $commentableType = match($type) {
            'post' => Post::class,
            'comment' => Comment::class,
            default => $type
        };

        return new self(
            body: $request->validated('body'),
            commentableId: $request->validated('commentable_id'),
            commentableType: $commentableType,
            userId: auth()->id()
        );
    }

    public static function fromPostComment(CreateCommentRequest $request, int $postId): self
    {
        return new self(
            body: $request->validated('body'),
            commentableId: $postId,
            commentableType: Post::class,
            userId: auth()->id()
        );
    }

    public static function fromCommentReply(CreateCommentRequest $request, int $commentId): self
    {
        return new self(
            body: $request->validated('body'),
            commentableId: $commentId,
            commentableType: Comment::class,
            userId: auth()->id()
        );
    }

    public function toArray(): array
    {
        return [
            'body' => $this->body,
            'commentable_id' => $this->commentableId,
            'commentable_type' => $this->commentableType,
            'user_id' => $this->userId,
        ];
    }
}
