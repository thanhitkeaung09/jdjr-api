<?php

declare(strict_types=1);

namespace App\Actions\V1\Otps;

use App\DataObjects\V1\Otps\ConfirmData;
use App\Exceptions\InvalidOtpException;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Laravel\Sanctum\NewAccessToken;

final readonly class ConfirmOtp
{
    public function handle(ConfirmData $data): NewAccessToken
    {
        /** @var User */
        $user = User::query()->whereGmailLogin($data->email)->first();

        /** @var ?Otp */
        $otp = Otp::query()->where('email', $data->email)->where('otp', $data->otp)->first();

        if ('production' === Config::get('app.env')) {
            $this->checkOtpValid($otp);
        }

        $this->verified($user);

        return $this->generateToken($user);
    }

    private function checkOtpValid(Otp|null $otp): void
    {
        if ( ! $otp) {
            throw new InvalidOtpException(
                message: \strval(\trans('message.exceptions.invalid_otp')),
            );
        }

        if ($otp->expired_at->lessThan(now())) {
            throw new InvalidOtpException(\strval(\trans('message.exceptions.otp_expired')));
        }
    }

    private function verified(User $user): void
    {
        $user->update(['email_verified_at' => now()]);
    }

    private function generateToken(User $user): NewAccessToken
    {
        $user->revokeTokens();

        return $user->createToken($user->email);
    }
}
