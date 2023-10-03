<?php

declare(strict_types=1);

namespace App\Actions\V1\Auth;

use App\Actions\V1\Users\CreateUserDefaultFolder;
use App\DataObjects\V1\Auth\SocialLoginData;
use App\Models\User;
use App\Services\FileStorage\FileStorage;
use Laravel\Sanctum\NewAccessToken;

final readonly class SocialLogin
{
    public function __construct(
        private FileStorage $fileStorage,
        private CreateUserDefaultFolder $createUserDefaultFolder,
    ) {
    }

    public function handle(SocialLoginData $data): NewAccessToken
    {
        /** @var ?User $user */
        $user = User::query()->whereSocialLogin(
            type: $data->loginType,
            id: $data->loginId
        )->first();

        if (null === $user) {
            $user = $this->createUser($data);

            $this->createUserDefaultFolder->handle($user);
        }

        return $user->createToken(strval($data->loginId));
    }

    private function createUser(SocialLoginData $data): User
    {
        $profile = $this->createProfile($data->profile);

        return User::query()->create([
            ...$data->toArray(),
            'profile' => $profile
        ]);
    }

    private function createProfile(string|null $profile): ?string
    {
        if (null === $profile) {
            return null;
        }

        return $this->fileStorage->upload(
            folder: \strval(\config('folders.profiles')),
            file: $profile,
        );
    }
}
