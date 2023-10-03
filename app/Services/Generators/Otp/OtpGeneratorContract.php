<?php

declare(strict_types=1);

namespace App\Services\Generators\Otp;

interface OtpGeneratorContract
{
    public function generate(): string;
}
