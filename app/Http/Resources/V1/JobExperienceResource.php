<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read string $id
 * @property-read string $duration
 * @property-read object{range: string, position_name: string} $salary
 */
final class JobExperienceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'duration' => $this->duration,
            'position' => $this->salary->position_name,
            'salary' => $this->salary->range,
            'level' => new LevelResource(
                resource: $this->whenLoaded('level'),
            ),
        ];
    }
}
