<?php

namespace App\Actions\Auth;

use App\DTOs\Auth\RegisterDTO;
use App\Repositories\Contracts\UserRepositoryInterface;

class RegisterAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function execute(RegisterDTO $dto): array
    {
            $user = $this->userRepository->create($dto->toArray());
            $token = $user->createToken('api-token')->plainTextToken;

        return [
                'user' => $user,
                'token' => $token,
            ];
    }
}
