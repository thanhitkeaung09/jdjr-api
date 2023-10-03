<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Job;
use App\Models\Qualification;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Qualification>
 */
final class QualificationFactory extends Factory
{
    protected $model = Qualification::class;

    public function definition(): array
    {
        return [
            'job_id' => Job::factory(),
            'icon' => 'qualifications/' . Str::random() . '.png',
            'text' => $this->faker->sentence(10, false),
        ];
    }
}
