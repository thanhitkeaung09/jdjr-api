<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read string $id
 * @property-read string $version
 * @property-read string $build_no
 * @property-read bool $is_forced_updated
 * @property-read string $ios_link
 * @property-read string $android_link
 */
final class AppVersionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'version' => $this->version,
            'buildNo' => $this->build_no,
            'isForcedUpdated' => $this->is_forced_updated,
            'iosLink' => $this->ios_link,
            'androidLink' => $this->android_link,
        ];
    }
}
