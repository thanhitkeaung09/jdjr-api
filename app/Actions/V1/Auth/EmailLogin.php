<?php

declare(strict_types=1);

namespace App\Actions\V1\Auth;

use App\Actions\V1\Otps\ResendOtp;
use App\DataObjects\V1\Auth\EmailLoginData;
use App\Exceptions\EmailVerifyException;
use App\Exceptions\LoginException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\NewAccessToken;

final readonly class EmailLogin
{
    public function __construct(
        private ResendOtp $resendOtp,
    ) {
    }

    public function handle(EmailLoginData $data): NewAccessToken
    {
        /** @var ?User $user */
        $user = User::query()
            ->whereGmailLogin($data->email)
            ->first();

        if ( ! $user || ! Hash::check($data->password, $user->password)) {
            throw new LoginException(\trans('message.exceptions.wrong_password'));
        }

        if (null === $user->email_verified_at) {
            $this->resendOtp->handle($user->email);

            throw new EmailVerifyException(
                message: \trans('message.exceptions.verified'),
                code: Http::NOT_ACCEPTABLE->value,
            );
        }

        return $user->createToken($data->email);
    }
}
