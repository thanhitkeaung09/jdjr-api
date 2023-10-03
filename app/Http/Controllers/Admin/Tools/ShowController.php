<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Tools;

use App\Http\Resources\V1\ToolResource;
use App\Http\Responses\V1\ModelResponse;
use App\Models\Tool;
use Illuminate\Contracts\Support\Responsable;

final readonly class ShowController
{
    public function __invoke(Tool $tool): Responsable
    {
        return new ModelResponse(
            data: new ToolResource(
                resource: $tool,
            ),
        );
    }
}
