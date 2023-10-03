<?php

declare(strict_types=1);

namespace App\Actions\V1\Otps;

use App\Models\Otp;
use App\Services\Generators\Otp\OtpGeneratorContract;

final readonly class CreateOtp
{
    public function __construct(
        private OtpGeneratorContract $otpGenerator,
    ) {
    }

    public function handle(string $email): Otp
    {
        return Otp::query()->updateOrCreate(
            attributes: [
                'email' => $email,
            ],
            values: [
                'otp' => $this->otpGenerator->generate(),
                'expired_at' => now()->addMinute(),
            ],
        );
    }
}
