<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
final class NewsFactory extends Factory
{
    protected $model = News::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(12, false),
            'short_body' => $this->faker->paragraph(2, false),
            'body' => $this->faker->randomHtml(),
            'thumbnail' => $this->faker->image(width: 100, height: 100),
        ];
    }
}
