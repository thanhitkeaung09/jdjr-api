<?php

declare(strict_types=1);

namespace App\Actions\V1\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

final readonly class CheckOwner
{
    public function handle(User $user): void
    {
        throw_if(
            $user->isNot(Auth::user()),
            AccessDeniedHttpException::class,
            \trans('message.exceptions.permission_denied'),
        );
    }
}
