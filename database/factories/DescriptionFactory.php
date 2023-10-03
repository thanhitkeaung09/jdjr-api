<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Description;
use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Description>
 */
final class DescriptionFactory extends Factory
{
    protected $model = Description::class;

    public function definition(): array
    {
        return [
            'job_id' => Job::factory(),
            'title' => $this->faker->sentence(12, false),
            'body' => $this->faker->paragraph(10, false),
        ];
    }
}
