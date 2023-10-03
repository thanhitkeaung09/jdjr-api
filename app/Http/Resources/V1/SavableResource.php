<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Enums\SavableType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read string $savable_id
 * @property-read string $folder_id
 * @property-read string $savable_type
 * @property-read string $savable_type
 * @property-read string $user_id
 */
final class SavableResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "savableId" => $this->savable_id,
            "folderId" => $this->folder_id,
            "savableType" => SavableType::fromModel($this->savable_type),
            "userId" => $this->user_id,
        ];
    }
}
