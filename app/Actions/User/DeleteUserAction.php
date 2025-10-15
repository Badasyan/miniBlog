<?php

namespace App\Actions\User;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class DeleteUserAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function execute(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            $user->tokens()->delete();

            return $this->userRepository->delete($user);
        });
    }
}
