<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Job;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
final class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition(): array
    {
        return [
            'job_id' => Job::factory(),
            'user_id' => User::factory(),
            'question' => $this->faker->sentence(10, false),
            'answer' => $this->faker->sentence(20, false),
            'is_favourited' => $this->faker->randomElement([true, false]),
        ];
    }

    public function favourited(): QuestionFactory
    {
        return $this->state(
            state: fn (array $attributes) => [
                'is_favourited' => true,
            ],
        );
    }
}
