<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Experience;
use App\Models\Level;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Experience>
 */
final class ExperienceFactory extends Factory
{
    protected $model = Experience::class;

    public function definition(): array
    {
        return [
            'duration' => $this->faker->numberBetween(1, 10) . ' years',
            'level_id' => Level::factory(),
        ];
    }

    public function withLevel(string $level): ExperienceFactory
    {
        return $this->state(
            state: fn (array $attributes) => [
                'level_id' => Level::factory()->create([
                    'name' => $level,
                ]),
            ],
        );
    }
}
