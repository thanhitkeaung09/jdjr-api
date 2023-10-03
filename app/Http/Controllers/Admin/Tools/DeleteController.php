<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Tools;

use App\Actions\V1\Tools\DeleteTool;
use App\Http\Responses\V1\MessageResponse;
use App\Models\Tool;
use Illuminate\Contracts\Support\Responsable;

final readonly class DeleteController
{
    public function __construct(
        private DeleteTool $deleteTool,
    ) {
    }

    public function __invoke(Tool $tool): Responsable
    {
        $status = $this->deleteTool->handle($tool);

        return new MessageResponse(
            data: [
                'message' => $status ?
                    \trans('message.delete.success') :
                    \trans('message.delete.fail'),
            ],
        );
    }
}
