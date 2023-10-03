<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ApplicationKey;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApplicationKey>
 */
final class ApplicationKeyFactory extends Factory
{
    protected $model = ApplicationKey::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'app_id' => ApplicationKey::generateAppId(),
            'app_secrete' => ApplicationKey::generateAppSecrete(),
            'obsoleted' => false,
        ];
    }

    public function obsoleted(): ApplicationKeyFactory
    {
        return $this->state(
            state: fn (array $attributes) => [
                'obsoleted' => true,
            ],
        );
    }
}
