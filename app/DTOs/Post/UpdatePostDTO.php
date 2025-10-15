<?php

namespace App\DTOs\Post;

use App\Http\Requests\Post\UpdatePostRequest;

class UpdatePostDTO
{
    public function __construct(
        public readonly ?string $body = null,
        public readonly ?bool $isActive = null
    ) {}

    public static function fromRequest(UpdatePostRequest $request): self
    {
        return new self(
            body: $request->validated('body'),
            isActive: $request->validated('is_active')
        );
    }

    public function toArray(): array
    {
        $data = [];

        if ($this->body !== null) {
            $data['body'] = $this->body;
        }

        if ($this->isActive !== null) {
            $data['is_active'] = $this->isActive;
        }

        return $data;
    }
}
