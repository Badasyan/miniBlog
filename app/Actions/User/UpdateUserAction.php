<?php

namespace App\Actions\User;

use App\DTOs\User\UpdateUserDTO;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UpdateUserAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(User $user, UpdateUserDTO $dto): User
    {
        return $this->userRepository->update($user, $dto->toArray());
    }
}
