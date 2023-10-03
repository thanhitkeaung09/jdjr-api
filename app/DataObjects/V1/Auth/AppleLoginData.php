<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Auth;

use App\Enums\LoginType;
use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class AppleLoginData implements DataObjectContract
{
    public function __construct(
        public ?string $name,
        public ?string $email,
        public LoginType $loginType,
        public string $loginId,
    ) {
    }

    /**
     * @param array{name?:string,email?:string,login_id:string} $attributes
     *
     * @return AppleLoginData
     */
    public static function of(array $attributes): AppleLoginData
    {
        return new AppleLoginData(
            name: $attributes['name'],
            email: $attributes['email'],
            loginType: LoginType::APPLE,
            loginId: $attributes['login_id'],
        );
    }

    public function isInvalid(): bool
    {
        return ! $this->name || ! $this->email;
    }

    /**
     * @return array{name?:string,email?:string,phone?:string,login_type:string,login_id:string}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'login_id' => $this->loginId,
            'login_type' => $this->loginType->value,
        ];
    }
}
