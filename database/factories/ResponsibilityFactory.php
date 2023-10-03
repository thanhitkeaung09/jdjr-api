<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Job;
use App\Models\Responsibility;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Responsibility>
 */
final class ResponsibilityFactory extends Factory
{
    protected $model = Responsibility::class;

    public function definition(): array
    {
        return [
            'job_id' => Job::factory(),
            'icon' => 'responsibilities/' . Str::random() . '.png',
            'text' => $this->faker->sentence(10, false),
        ];
    }
}
