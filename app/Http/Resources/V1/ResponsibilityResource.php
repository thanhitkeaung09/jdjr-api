<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read string $id
 * @property-read string $icon
 * @property-read string $text
 */
final class ResponsibilityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'icon' => route(
                name: 'api:v1:images:show',
                parameters: [
                    'path' => $this->icon,
                ]
            ),
            'text' => $this->text,
        ];
    }
}
