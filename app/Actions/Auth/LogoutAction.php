<?php

namespace App\Actions\Auth;

use App\Models\User;

class LogoutAction
{
    public function execute(User $user): bool
    {
        $user->currentAccessToken()->delete();

        return true;
    }
}
