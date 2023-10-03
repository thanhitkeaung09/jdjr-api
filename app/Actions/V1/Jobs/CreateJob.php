<?php

declare(strict_types=1);

namespace App\Actions\V1\Jobs;

use App\Actions\V1\Notifications\SendNotificationToUsers;
use App\DataObjects\V1\Jobs\CareerPath;
use App\DataObjects\V1\Jobs\Experience as ExperienceData;
use App\DataObjects\V1\Jobs\NewJob;
use App\DataObjects\V1\Jobs\Qualification;
use App\DataObjects\V1\Jobs\Responsibility;
use App\DataObjects\V1\Notifications\Notification;
use App\Enums\NotificationType;
use App\Jobs\NotificationCreate;
use App\Models\Job;
use App\Services\FileStorage\FileStorage;
use Illuminate\Support\Arr;

final readonly class CreateJob
{
    public function __construct(
        private SendNotificationToUsers $sendNotificationToUsers,
        private FileStorage $fileStorage,
    ) {
    }

    public function handle(NewJob $data)
    {
        $path = $this->fileStorage->upload(
            folder: \config('folders.jobs'),
            file: $data->image,
        );

        /** @var Job */
        $job = Job::query()->create([
            ...$data->toArray(),
            'icon' => $path,
        ]);

        $job->description()->create($data->description->toArray());

        $job->skills()->attach($data->skills);
        $job->tools()->attach($data->tools);

        $this->createCareerPaths($job, $data->careerPaths);

        $this->createQualifications($job, $data->qualifications);

        $this->createResponsibilities($job, $data->responsibilities);

        $this->createExperiences($job, $data->experiences);

        $this->sendNotifications($job);

        return $job;
    }

    private function sendNotifications(Job $job): void
    {
        NotificationCreate::dispatch(
            type: NotificationType::JOB->toModelString(),
            id: $job->id,
        );

        $this->sendNotificationToUsers->handle(
            notification: Notification::of([
                'id' => $job->id,
                'title' => $job->title,
                'body' => $job->description?->title ?? '',
                'type' => NotificationType::JOB,
            ]),
        );
    }

    private function createCareerPaths(Job $job, array $careerPaths): void
    {
        if (\count($careerPaths) > 0) {
            $job->careerPaths()->createMany(
                records: Arr::map(
                    array: $careerPaths,
                    callback: fn (CareerPath $item) => $item->toArray()
                ),
            );
        }
    }

    private function createQualifications(Job $job, array $qualifications): void
    {
        if (\count($qualifications) > 0) {
            $job->qualifications()->createMany(
                records: Arr::map(
                    array: $qualifications,
                    callback: fn (Qualification $item) =>
                    [
                        ...$item->toArray(),
                        'icon' => $this->fileStorage->upload(
                            folder: \config('folders.qualifications'),
                            file: $item->icon,
                        ),
                    ]
                ),
            );
        }
    }

    private function createResponsibilities(Job $job, array $responsibilities): void
    {
        if (\count($responsibilities) > 0) {
            $job->responsibilities()->createMany(
                records: Arr::map(
                    array: $responsibilities,
                    callback: fn (Responsibility $item) => [
                        ...$item->toArray(),
                        'icon' => $this->fileStorage->upload(
                            folder: \config('folders.responsibilities'),
                            file: $item->icon,
                        ),
                    ]
                ),
            );
        }
    }

    private function createExperiences(Job $job, array $experiences): void
    {
        if (\count($experiences) > 0) {
            $experienceIds = collect($experiences)->flatMap(function (ExperienceData $data) {
                return [
                    $data->id => $data->toArray(),
                ];
            })->all();

            $job->experiences()->attach($experienceIds);
        }
    }
}
