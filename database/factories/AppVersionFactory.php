<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AppVersion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AppVersion>
 */
final class AppVersionFactory extends Factory
{
    protected $model = AppVersion::class;

    public function definition(): array
    {
        return [
            'version' => '1.0',
            'build_no' => Str::random(20),
            'is_forced_updated' => false,
            'ios_link' => $this->faker->url(),
            'android_link' => $this->faker->url(),
        ];
    }
}
