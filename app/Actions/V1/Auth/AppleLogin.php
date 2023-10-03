<?php

declare(strict_types=1);

namespace App\Actions\V1\Auth;

use App\Actions\V1\Users\CreateUserDefaultFolder;
use App\DataObjects\V1\Auth\AppleLoginData;
use App\Exceptions\LoginException;
use App\Models\User;
use App\Services\FileStorage\FileStorage;
use Laravel\Sanctum\NewAccessToken;

final readonly class AppleLogin
{
    public function __construct(
        private FileStorage $fileStorage,
        private CreateUserDefaultFolder $createUserDefaultFolder,
    ) {
    }

    public function handle(AppleLoginData $data): NewAccessToken
    {
        /** @var ?User $user */
        $user = User::query()->whereSocialLogin(
            type: $data->loginType,
            id: $data->loginId
        )->first();

        $deletedUser = User::onlyTrashed()->whereSocialLogin(
            type: $data->loginType,
            id: $data->loginId
        )->first();

        $isNewUser = ! $user && ! $deletedUser;

        if ($isNewUser && $data->isInvalid()) {
            throw new LoginException('User name or email is required!');
        }

        if ($isNewUser) {
            $user = User::query()->create($data->toArray());
        }

        if ($deletedUser && ! $user) {
            $user = User::query()->create([
                ...$data->toArray(),
                'name' =>  $deletedUser->name,
                'email' => \explode('_', $deletedUser->email)[0],
            ]);
        }

        $this->createUserDefaultFolder->handle($user);

        return $user->createToken(strval($data->loginId));
    }
}
