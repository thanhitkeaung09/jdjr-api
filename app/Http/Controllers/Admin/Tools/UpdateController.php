<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Tools;

use App\Actions\V1\Tools\UpdateTool;
use App\Http\Requests\V1\Tools\UpsertRequest;
use App\Http\Responses\V1\MessageResponse;
use App\Models\Tool;
use Illuminate\Contracts\Support\Responsable;

final readonly class UpdateController
{
    public function __construct(
        private UpdateTool $updateTool,
    ) {
    }

    public function __invoke(Tool $tool, UpsertRequest $request): Responsable
    {
        $status = $this->updateTool->handle(
            tool: $tool,
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
