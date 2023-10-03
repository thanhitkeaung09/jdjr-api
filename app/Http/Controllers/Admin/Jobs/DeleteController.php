<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Jobs;

use App\Actions\V1\Jobs\DeleteJob;
use App\Http\Responses\V1\MessageResponse;
use App\Models\Job;

final readonly class DeleteController
{
    public function __construct(
        private DeleteJob $deleteJob,
    ) {
    }

    public function __invoke(Job $job)
    {
        $status = $this->deleteJob->handle($job);

        return new MessageResponse(
            data: [
                'message' => $status ?
                    \trans('message.delete.success') :
                    \trans('message.delete.fail'),
            ],
        );
    }
}
