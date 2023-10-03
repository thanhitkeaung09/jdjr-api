<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Jobs;

use App\Actions\V1\Jobs\TogglePopular;
use App\Http\Responses\V1\MessageResponse;
use App\Models\Job;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;

final readonly class PopularController
{
    public function __construct(
        private TogglePopular $togglePopular,
    ) {
    }

    public function __invoke(Job $job, Request $request): Responsable
    {
        $status = $this->togglePopular->handle($job, 'true' === $request->query('is_popular'));

        return new MessageResponse(
            data: [
                'message' => $request->query('is_popular'),
            ],
        );
    }
}
