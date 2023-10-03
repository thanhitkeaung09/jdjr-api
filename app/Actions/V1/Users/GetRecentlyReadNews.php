<?php

declare(strict_types=1);

namespace App\Actions\V1\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

final readonly class GetRecentlyReadNews
{
    public function handle(): Builder
    {
        /** @var User */
        $user = Auth::user();

        return $user->readedNews()->wherePivot(
            column: 'created_at',
            operator: ">=",
            value: now()->subHours(2)->toDateTimeString(),
        )->orderByPivot('created_at', 'desc')->getQuery();
    }
}
