<?php

declare(strict_types=1);

namespace App\Actions\V1\Users;

use App\Models\User;

final readonly class FetchUser
{
    public function handle(User $user): User
    {
        return $user->load([
            'experience',
            'location',
            'interests',
            'skills',
            'folders',
        ]);
    }
}
