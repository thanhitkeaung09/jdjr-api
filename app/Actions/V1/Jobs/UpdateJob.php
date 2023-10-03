<?php

declare(strict_types=1);

namespace App\Actions\V1\Jobs;

use App\DataObjects\V1\Jobs\CareerPath;
use App\DataObjects\V1\Jobs\NewJob;
use App\DataObjects\V1\Jobs\Qualification;
use App\DataObjects\V1\Jobs\Responsibility;
use App\DataObjects\V1\Jobs\Experience as ExperienceData;
use Illuminate\Support\Arr;
use App\Models\Job;
use App\Services\FileStorage\FileStorage;

final readonly class UpdateJob
{
    public function __construct(
        private FileStorage $fileStorage,
    ) {
    }

    public function handle(Job $job, NewJob $data): bool
    {
        $attributes = $data->toArray();

        if ($data->image) {
            $this->fileStorage->delete($job->icon);

            $attributes['icon'] = $this->fileStorage->upload(
                folder: \config('folders.jobs'),
                file: $data->image,
            );
        }

        $job->update($attributes);

        $job->description()->update($data->description->toArray());

        $job->skills()->sync($data->skills);
        $job->tools()->sync($data->tools);

        $this->updateCareerPaths($job, $data->careerPaths);
        $this->updateQualifications($job, $data->qualifications);
        $this->updateResponsibilities($job, $data->responsibilities);
        $this->updateExperiences($job, $data->experiences);
        return true;
    }

    private function updateCareerPaths(Job $job, array $careerPaths): void
    {
        if (\count($careerPaths) > 0) {
            $job->careerPaths()->delete();

            $job->careerPaths()->createMany(
                records: Arr::map(
                    array: $careerPaths,
                    callback: fn (CareerPath $item) => $item->toArray()
                ),
            );
        }
    }

    private function updateQualifications(Job $job, array $qualifications): void
    {
        $job->qualifications->each(
            fn ($qualification) =>
            $this->fileStorage->delete($qualification->icon)
        );

        $job->qualifications()->delete();

        if (\count($qualifications) > 0) {
            $job->qualifications()->createMany(
                records: Arr::map(
                    array: $qualifications,
                    callback: fn (Qualification $item) =>
                    [
                        ...$item->toArray(),
                        'icon' => is_string($item->icon) ? $item->icon : $this->fileStorage->upload(
                            folder: \config('folders.qualifications'),
                            file: $item->icon,
                        ),
                    ]
                ),
            );
        }
    }

    private function updateResponsibilities(Job $job, array $responsibilities): void
    {
        if (\count($responsibilities) > 0) {
            $job->responsibilities->each(
                fn ($responsibility) =>
                $this->fileStorage->delete($responsibility->icon)
            );

            $job->responsibilities()->delete();

            $job->responsibilities()->createMany(
                records: Arr::map(
                    array: $responsibilities,
                    callback: fn (Responsibility $item) => [
                        ...$item->toArray(),
                        'icon' => is_string($item->icon) ? $item->icon :
                            $this->fileStorage->upload(
                                folder: \config('folders.responsibilities'),
                                file: $item->icon,
                            ),
                    ]
                ),
            );
        }
    }

    private function updateExperiences(Job $job, array $experiences): void
    {
        if (\count($experiences) > 0) {
            $job->experiences()->detach();

            $experienceIds = collect($experiences)->flatMap(function (ExperienceData $data) {
                return [
                    $data->id => $data->toArray(),
                ];
            })->all();

            $job->experiences()->attach($experienceIds);
        }
    }
}
