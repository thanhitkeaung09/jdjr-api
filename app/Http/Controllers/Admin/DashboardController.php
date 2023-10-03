<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\V1\Dashboard\FetchAllCount;
use App\Http\Resources\V1\AllCountResource;
use App\Http\Responses\V1\ModelResponse;
use Illuminate\Contracts\Support\Responsable;

final readonly class DashboardController
{
    public function __construct(
        private FetchAllCount $query,
    ) {
    }

    public function __invoke(): Responsable
    {
        return new ModelResponse(
            data: new AllCountResource(
                resource: $this->query->handle(),
            ),
        );
    }
}
