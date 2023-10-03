<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Sanctum\NewAccessToken;

/**
 * @property-read NewAccessToken $resource
 */
final class AdminAuthResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'token' => $this->resource->plainTextToken,
            'roles' => RoleResource::collection(
                resource: $this->resource->accessToken->tokenable->roles->load('permissions'),
            ),
        ];
    }
}
