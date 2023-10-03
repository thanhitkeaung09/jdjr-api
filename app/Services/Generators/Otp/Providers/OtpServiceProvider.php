<?php

declare(strict_types=1);

namespace App\Services\Generators\Otp\Providers;

use App\Services\Generators\Otp\OtpGenerator;
use App\Services\Generators\Otp\OtpGeneratorContract;
use Illuminate\Support\ServiceProvider;

final class OtpServiceProvider extends ServiceProvider
{
    /**
     * @var array<string, string>
     */
    public $bindings = [
        OtpGeneratorContract::class => OtpGenerator::class,
    ];
}
