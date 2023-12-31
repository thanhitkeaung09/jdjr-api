<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Auth;

use App\Enums\LoginType;
use Illuminate\Support\Facades\Hash;
use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class EmailRegisterData implements DataObjectContract
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {
    }

    /**
     * @param array{name:string,email:string,password:string} $attributes
     *
     * @return EmailRegisterData
     */
    public static function of(array $attributes): EmailRegisterData
    {
        return new EmailRegisterData(
            name: $attributes['name'],
            email: $attributes['email'],
            password: $attributes['password'],
        );
    }

    /**
     * @return array{name:string,email:string,password:string,login_type:LoginType}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'login_type' => LoginType::GMAIL,
        ];
    }
}
