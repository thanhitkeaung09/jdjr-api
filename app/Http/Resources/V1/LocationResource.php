<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read string $id
 * @property-read string $name
 */
final class LocationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    public static function nullObj(): LocationResource
    {
        return new LocationResource(
            new Location([
                'id' => null,
                'name' => null
            ]),
        );
    }
}
