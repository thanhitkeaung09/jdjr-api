<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Experiences;

use App\Actions\V1\Experiences\UpdateExperience;
use App\Http\Requests\V1\Experiences\UpsertRequest;
use App\Http\Responses\V1\MessageResponse;
use App\Models\Experience;
use Illuminate\Contracts\Support\Responsable;

final readonly class UpdateController
{
    public function __construct(
        private UpdateExperience $updateExperience,
    ) {
    }

    public function __invoke(Experience $experience, UpsertRequest $request): Responsable
    {
        $status = $this->updateExperience->handle($experience, $request->payload());

        return new MessageResponse(
            data: [
                'message' => $status ?
                    trans('message.update.success') :
                    trans('message.update.fail'),
            ],
        );
    }
}
