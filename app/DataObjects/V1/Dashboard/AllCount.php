<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Dashboard;

use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class AllCount implements DataObjectContract
{
    public function __construct(
        public int $userCount,
        public int $newsCount,
        public int $jobsCount,
        public int $questionsCount,
    ) {
    }

    /**
     * @param array{user_count:int,news_count:int,jobs_count:int,questions_count:int} $attributes
     *
     * @return AllCount
     */
    public static function of(array $attributes): AllCount
    {
        return new AllCount(
            userCount: $attributes['user_count'],
            newsCount: $attributes['news_count'],
            jobsCount: $attributes['jobs_count'],
            questionsCount: $attributes['questions_count'],
        );
    }

    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [];
    }
}
