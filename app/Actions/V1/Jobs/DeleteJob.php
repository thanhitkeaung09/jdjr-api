<?php

declare(strict_types=1);

namespace App\Actions\V1\Jobs;

use App\Models\Experience;
use App\Models\Job;
use App\Services\FileStorage\FileStorage;
use Illuminate\Support\Facades\DB;

final readonly class DeleteJob
{
    public function __construct(
        private FileStorage $fileStorage,
    ) {
    }

    public function handle(Job $job): bool
    {
        $this->fileStorage->delete($job->icon);

        return DB::transaction(static function () use ($job) {
            $job->description()->delete();
            $job->skills()->detach();
            $job->tools()->detach();
            $job->careerPaths()->delete();
            $job->qualifications()->delete();
            $job->responsibilities()->delete();

            $experienceIds = $job->experiences()->pluck('id');
            $job->experiences()->detach();
            Experience::query()->whereIn('id', $experienceIds)->delete();

            $job->notifications()->delete();

            return $job->delete();
        });
    }
}
