<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\News;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
final class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        return [
            'notifiable_type' => News::class,
            'notifiable_id' => News::factory(),
            'user_id' => User::factory(),
            'is_readed' => $this->faker->randomElement([true, false]),
        ];
    }
}
