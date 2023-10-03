<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * @property-read Carbon $resource
 */
final class DateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'string' => $this->resource->toDateTimeString(),
            'human' => $this->resource->diffForHumans(),
        ];
    }
}
