<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Auth;

use Illuminate\Support\Collection;
use Laravel\Sanctum\NewAccessToken;
use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class AdminLoginData implements DataObjectContract
{
    public function __construct(
        public NewAccessToken $token,
        public Collection $roles,
        public Collection $permissions,
    ) {
    }

    /**
     * @param array{token:NewAccessToken,roles:Collection,permissions:Collection}
     *
     * @return AdminLoginData
     */
    public static function of(array $attributes): AdminLoginData
    {
        return new AdminLoginData(
            token: $attributes['token'],
            roles: $attributes['roles'],
            permissions: $attributes['permissions'],
        );
    }

    /**
     * @return array{token:string,roles:array,permissions:array}
     */
    public function toArray(): array
    {
        return [
            'token' => $this->token->plainTextToken,
            'roles' => $this->roles->values(),
            'permissions' => $this->permissions->values(),
        ];
    }
}
