<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Jobs;

use App\Actions\V1\Jobs\UpdateJob;
use App\Http\Requests\V1\Jobs\UpdateRequest;
use App\Http\Responses\V1\MessageResponse;
use App\Models\Job;
use Illuminate\Contracts\Support\Responsable;

final readonly class UpdateController
{
    public function __construct(
        private UpdateJob $updateJob,
    ) {
    }

    public function __invoke(Job $job, UpdateRequest $request): Responsable
    {
        $status = $this->updateJob->handle(
            job: $job,
            data: $request->payload(),
        );

        return new MessageResponse(
            data: [
                'message' => $status ?
                    \trans('message.update.success') :
                    \trans('message.update.fail'),
            ],
        );
    }
}
