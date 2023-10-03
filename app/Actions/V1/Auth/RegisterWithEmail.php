<?php

declare(strict_types=1);

namespace App\Actions\V1\Auth;

use App\Actions\V1\Otps\CreateOtp;
use App\Actions\V1\Otps\SendOtp;
use App\Actions\V1\Users\CreateUserDefaultFolder;
use App\DataObjects\V1\Auth\EmailRegisterData;
use App\Exceptions\EmailRegisterException;
use App\Models\User;
use JustSteveKing\StatusCode\Http;

final readonly class RegisterWithEmail
{
    public function __construct(
        private CreateOtp $createOtp,
        private SendOtp $sendOtp,
        private CreateUserDefaultFolder $createUserDefaultFolder,
    ) {
    }

    public function handle(EmailRegisterData $data): void
    {
        $this->checkAlreadyRegister($data->email);

        $user = User::query()->create(
            attributes: $data->toArray(),
        );

        $this->createUserDefaultFolder->handle($user);

        $otp = $this->createOtp->handle(
            email: $data->email,
        );

        $this->sendOtp->handle(
            user: $user,
            otp: $otp
        );
    }

    /**
     * @throws EmailRegisterException
     */
    private function checkAlreadyRegister(string $email): void
    {
        if (null !== User::query()->where('email', $email)->first()) {
            throw new EmailRegisterException(
                message: \trans('message.exceptions.email_not_verified'),
                code: Http::NOT_ACCEPTABLE->value,
            );
        }
    }
}
