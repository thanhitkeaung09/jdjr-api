<?php

declare(strict_types=1);

namespace App\Actions\V1\News;

use App\DataObjects\V1\News\LikeData;
use App\Events\LikeEvent;
use App\Models\News;

final readonly class ToggleLikeToNews
{
    public function handle(News $news, LikeData $data): void
    {
        if ($data->like) {
            $data->user->likedNews()->attach($news);
        } else {
            $data->user->likedNews()->detach($news);
        }

        event(new LikeEvent($news, $data->like));
    }
}
