<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Folder;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * @property-read News $resource
 * @property-read string $id
 * @property-read string $title
 * @property-read string $short_body
 * @property-read string $body
 * @property-read string $thumbnail
 * @property-read Carbon $created_at
 */
final class NewsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'shortBody' => $this->short_body,
            'body' => $this->body,
            'thumbnail' => route(
                name: 'api:v1:images:show',
                parameters: [
                    'path' => $this->thumbnail,
                ],
            ),
            'created' => new DateResource(
                resource: $this->created_at,
            ),
            'updated' => new DateResource(
                resource: $this->updated_at,
            ),
            'saved' => $this->resource->currentUserSavedFolders()->count() > 0,
            'savable' => $this->when(
                condition: $this->resource->folders()->first() instanceof Folder,
                value: fn () => new SavableResource(
                    resource: $this->resource->folders()->first()->savable
                ),
                default: null,
            ),
            'liked' => $this->resource->currentUserLikes()->count() > 0,
            'likedCount' => $this->whenCounted('likes'),
        ];
    }
}
