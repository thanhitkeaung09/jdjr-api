<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Job;
use App\Models\Location;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
final class JobFactory extends Factory
{
    protected $model = Job::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(10, false),
            'icon' => $this->faker->image(width: 50, height: 50),
            'subcategory_id' => Subcategory::factory(),
            'location_id' => Location::factory(),
            'tools_remark' => $this->faker->sentence(10, false),
        ];
    }

    public function withPosition(string $position): JobFactory
    {
        return $this->state(
            state: fn (array $attributes) => [
                'title' => $position,
            ],
        );
    }
}
