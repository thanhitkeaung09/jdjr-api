<?php

declare(strict_types=1);

namespace App\Http\Resources\V1\Notifications;

use App\Http\Resources\V1\DateResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * @property-read string $id
 * @property-read boolean $is_readed
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
final class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'isReaded' => $this->is_readed,
            'created' => new DateResource(
                resource: $this->created_at,
            ),
            'updated' => new DateResource(
                resource: $this->updated_at,
            ),
            'notifiable' => new NotifiableResource(
                resource: $this->whenLoaded('notifiable'),
            ),
        ];
    }
}
