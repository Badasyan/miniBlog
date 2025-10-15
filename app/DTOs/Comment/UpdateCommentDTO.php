<?php

namespace App\DTOs\Comment;

use App\Http\Requests\Comment\UpdateCommentRequest;

class UpdateCommentDTO
{
    public function __construct(
        public readonly string $body
    ) {}

    public static function fromRequest(UpdateCommentRequest $request): self
    {
        return new self(
            body: $request->validated('body')
        );
    }

    public function toArray(): array
    {
        return [
            'body' => $this->body,
        ];
    }
}
