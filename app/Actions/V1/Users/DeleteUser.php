<?php

declare(strict_types=1);

namespace App\Actions\V1\Users;

use App\Models\User;

final readonly class DeleteUser
{
    public function handle(User $user): bool
    {
        $this->deleteRelations($user);

        $user->update(['email' => $user->email . '_' . now()->timestamp]);

        return $user->delete();
    }

    private function deleteRelations(User $user): void
    {
        $user->folders()->delete();

        $user->likedNews()->detach();

        $user->readedNews()->detach();

        $user->skills()->detach();

        $user->interests()->detach();

        $user->questions()->delete();

        $user->revokeTokens();
    }
}
