<?php

declare(strict_types=1);

namespace App\Actions\V1\Dashboard;

use App\DataObjects\V1\Dashboard\AllCount;
use App\Models\Job;
use App\Models\News;
use App\Models\Question;
use App\Models\User;

final readonly class FetchAllCount
{
    public function handle(): AllCount
    {
        return AllCount::of([
            'user_count' => User::query()->count(),
            'news_count' => News::query()->count(),
            'jobs_count' => Job::query()->count(),
            'questions_count' => Question::query()->whereNull('answer')->count(),
        ]);
    }
}
