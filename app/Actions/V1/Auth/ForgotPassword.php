<?php

declare(strict_types=1);

namespace App\Actions\V1\Auth;

use App\Actions\V1\Otps\CreateOtp;
use App\Actions\V1\Otps\SendOtp;
use App\Models\User;

final readonly class ForgotPassword
{
    public function __construct(
        private CreateOtp $createOtp,
        private SendOtp $sendOtp,
    ) {
    }

    public function handle(string $email): void
    {
        /** @var User */
        $user = User::query()->whereGmailLogin($email)->first();

        $otp = $this->createOtp->handle(
            email: $email,
        );

        $this->sendOtp->handle(
            user: $user,
            otp: $otp,
            forgot: true,
        );
    }
}
