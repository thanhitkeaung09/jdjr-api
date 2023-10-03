<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read string $id
 * @property-read string $question
 * @property-read string $answer
 */
final class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'question' => $this->question,
            'answer' => $this->answer,
            'isFavourited' => $this->is_favourited,
            'job' => new JobResource(
                resource: $this->whenLoaded('job'),
            ),
            'user' => new UserResource(
                resource: $this->whenLoaded('user'),
            ),
        ];
    }
}
