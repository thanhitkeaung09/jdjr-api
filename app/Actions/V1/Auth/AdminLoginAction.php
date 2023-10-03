<?php

declare(strict_types=1);

namespace App\Actions\V1\Auth;

use App\DataObjects\V1\Auth\EmailLoginData;
use App\Exceptions\LoginException;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\NewAccessToken;

final readonly class AdminLoginAction
{
    public function handle(EmailLoginData $data): NewAccessToken
    {
        /** @var ?Admin $admin */
        $admin = Admin::query()
            ->where('email', $data->email)
            ->first();

        if ( ! $admin || ! Hash::check($data->password, $admin->password)) {
            throw new LoginException('User credentials did not match!');
        }

        return $admin->createToken($data->email);
    }
}
