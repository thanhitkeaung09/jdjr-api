<?php

declare(strict_types=1);

namespace App\Actions\V1\Auth;

use App\Models\Admin;
use App\Models\User;

final readonly class LogoutAction
{
    public function handle(User|Admin $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
