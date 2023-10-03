<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Auth;

use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class EmailLoginData implements DataObjectContract
{
    public function __construct(
        public string $email,
        public string $password,
    ) {
    }

    /**
     * @param array{email:string,password:string} $attributes
     *
     * @return EmailLoginData
     */
    public static function of(array $attributes): EmailLoginData
    {
        return new EmailLoginData(
            email: $attributes['email'],
            password: $attributes['password'],
        );
    }

    /**
     * @return array{email:string,password:string}
     */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
