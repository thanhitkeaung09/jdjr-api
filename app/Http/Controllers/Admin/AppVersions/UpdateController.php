<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\AppVersions;

use App\Actions\V1\AppVersions\UpdateAppVersion;
use App\Http\Requests\V1\AppVersion\UpdateRequest;
use App\Http\Responses\V1\MessageResponse;
use App\Models\AppVersion;
use Illuminate\Contracts\Support\Responsable;

final readonly class UpdateController
{
    public function __construct(
        private UpdateAppVersion $updateAppVersion
    ) {
    }

    public function __invoke(
        AppVersion $appVersion,
        UpdateRequest $request,
    ): Responsable {
        $status = $this->updateAppVersion->handle(
            appVersion: $appVersion,
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
