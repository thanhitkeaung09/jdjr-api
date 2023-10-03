<?php

declare(strict_types=1);

namespace App\Actions\V1\Jobs;

use App\Models\Job;
use App\Models\PopularSearch;

final readonly class TogglePopular
{
    public function handle(Job $job, bool $isPopular): mixed
    {
        if ($isPopular) {
            PopularSearch::query()->create([
                'job_id' => $job->id,
                'job_title' => $job->title,
            ]);
        } else {
            PopularSearch::query()->where('job_id', $job->id)->delete();
        }

        return $isPopular;
    }
}
