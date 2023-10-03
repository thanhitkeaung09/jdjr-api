<?php

declare(strict_types=1);

namespace App\Http\Resources\V1\Notifications;

use App\Enums\NotificationType;
use App\Models\Job;
use App\Models\News;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read News|Job|Question $resource
 */
final class NotifiableResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => NotificationType::fromModel($this->getMorphClass()),
            'title' => $this->title ?? $this->question,
        ];
    }
}
