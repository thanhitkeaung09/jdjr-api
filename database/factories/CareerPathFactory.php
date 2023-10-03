<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CareerPath;
use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CareerPath>
 */
final class CareerPathFactory extends Factory
{
    protected $model = CareerPath::class;

    public function definition(): array
    {
        return [
            'job_id' => Job::factory(),
            'title' => $this->faker->sentence(variableNbWords: false),
            'body' => $this->faker->sentence(20, false),
        ];
    }
}
