<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Admins;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class AdminData implements DataObjectContract
{
    public function __construct(
        public string $name,
        public string $email,
        public string|null $password,
        public string|null $confirmedPassword,
        public array|null $roles,
    ) {
    }

    /**
     * @param array{name:string,email:string,password:string,password_confirmation:string,roles?:array} $attributes
     */
    public static function of(array $attributes): AdminData
    {
        return new AdminData(
            name: $attributes['name'],
            email: $attributes['email'],
            password: Arr::exists($attributes, 'password') ? $attributes['password'] : null,
            confirmedPassword: Arr::exists($attributes, 'password_confirmation') ? $attributes['password_confirmation'] : null,
            roles: Arr::exists($attributes, 'roles') ? $attributes['roles'] : null,
        );
    }

    /**
     * @return array{name:string,email:string,password:string}
     */
    public function toArray(): array
    {
        $password = null;

        if (null !== $this->password) {
            // Check password is plain text or hash text.
            $password = Hash::info($this->password)['algo'] ? $this->password : Hash::make($this->password);
        }

        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $password,
        ];
    }
}
