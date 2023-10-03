<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Otps;

use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class ConfirmData implements DataObjectContract
{
    public function __construct(
        public string $email,
        public string $otp,
    ) {
    }

    /**
     * @param array{email:string,otp:string} $attributes
     *
     * @return ConfirmData
     */
    public static function of(array $attributes): ConfirmData
    {
        return new ConfirmData(
            email: $attributes['email'],
            otp: $attributes['otp'],
        );
    }

    /**
     * @return array{email:string,otp:string}
     */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'otp' => $this->otp,
        ];
    }
}
