<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read string $id
 * @property-read string $name
 */
final class FolderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'jobs' => JobResource::collection(
                resource: $this->whenLoaded('jobs'),
            ),
            'news' => NewsResource::collection(
                resource: $this->whenLoaded('news'),
            ),
        ];
    }
}
