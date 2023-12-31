<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Auth;

use App\Enums\LoginType;
use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class SocialLoginData implements DataObjectContract
{
    public function __construct(
        public string $name,
        public ?string $email,
        public ?string $phone,
        public LoginType $loginType,
        public string $loginId,
        public ?string $profile,
    ) {
    }

    /**
     * @param array{name:string,email?:string,phone?:string,login_type:string,login_id:string,profile?:string} $attributes
     *
     * @return SocialLoginData
     */
    public static function of(array $attributes): SocialLoginData
    {
        return new SocialLoginData(
            name: $attributes['name'],
            email: $attributes['email'],
            phone: $attributes['phone'],
            loginType: LoginType::from($attributes['login_type']),
            loginId: $attributes['login_id'],
            profile: $attributes['profile'],
        );
    }

    /**
     * @return array{name:string,email?:string,phone?:string,login_type:string,login_id:string}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'login_id' => $this->loginId,
            'login_type' => $this->loginType->value,
        ];
    }
}
