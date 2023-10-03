<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\DataObjects\V1\Dashboard\AllCount;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read AllCount $resource
 */
final class AllCountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'userCount' => $this->resource->userCount,
            'newsCount' => $this->resource->newsCount,
            'jobsCount' => $this->resource->jobsCount,
            'questionsCount' => $this->resource->questionsCount,
        ];
    }
}
