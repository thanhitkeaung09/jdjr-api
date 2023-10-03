<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read string $job_id
 * @property-read string $job_title
 */
final class PopularResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'jobId' => $this->job_id,
            'jobTitle' => $this->job_title,
        ];
    }
}
