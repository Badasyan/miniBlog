<?php

namespace App\DTOs\Post;

use App\Http\Requests\Post\CreatePostRequest;

class CreatePostDTO
{
    public function __construct(
        public readonly string $body,
        public readonly bool $isActive,
        public readonly int $userId
    ) {}

    public static function fromRequest(CreatePostRequest $request): self
    {
        return new self(
            body: $request->validated('body'),
            isActive: true,
            userId: auth()->id()
        );
    }

    public function toArray(): array
    {
        return [
            'body' => $this->body,
            'is_active' => $this->isActive,
            'user_id' => $this->userId,
        ];
    }
}
