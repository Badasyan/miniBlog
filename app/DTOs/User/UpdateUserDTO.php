<?php

namespace App\DTOs\User;

use App\Http\Requests\User\UpdateUserRequest;

class UpdateUserDTO
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $email = null,
        public readonly ?string $password = null
    ) {}

    public static function fromRequest(UpdateUserRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
            email: $request->validated('email'),
            password: $request->validated('password')
        );
    }

    public function toArray(): array
    {
        $data = [];

        if ($this->name !== null) {
            $data['name'] = $this->name;
        }

        if ($this->email !== null) {
            $data['email'] = $this->email;
        }

        if ($this->password !== null) {
            $data['password'] = $this->password;
        }

        return $data;
    }
}
