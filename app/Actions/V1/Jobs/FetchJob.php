<?php

declare(strict_types=1);

namespace App\Actions\V1\Jobs;

use App\Models\Job;

final readonly class FetchJob
{
    public function handle(Job $job, bool $questionFilter = true): Job
    {
        return $job->load([
            'description',
            'skills',
            'tools',
            'questions' => function ($query) use ($questionFilter): void {
                if ($questionFilter) {
                    $query->where('is_favourited', true);
                }
            },
            'qualifications',
            'responsibilities',
            'careerPaths',
            'experiences',
        ]);
    }
}
