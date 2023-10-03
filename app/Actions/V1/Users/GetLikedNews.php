<?php

declare(strict_types=1);

namespace App\Actions\V1\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

final readonly class GetLikedNews
{
    public function handle(): BelongsToMany
    {
        /** @var User */
        $user = Auth::user();

        return $user->likedNews()->orderByPivot('created_at', 'desc');
    }
}
