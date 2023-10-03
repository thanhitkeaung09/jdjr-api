<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\News;

use App\Actions\V1\News\ToggleLikeToNews;
use App\Http\Requests\V1\News\LikeRequest;
use App\Http\Responses\V1\MessageResponse;
use App\Models\News;
use Illuminate\Contracts\Support\Responsable;

final readonly class LikeController
{
    public function __construct(
        private ToggleLikeToNews $toggleLikeToNews,
    ) {
    }

    public function __invoke(News $news, LikeRequest $request): Responsable
    {
        $payload = $request->payload();

        $this->toggleLikeToNews->handle($news, $payload);

        return new MessageResponse(
            data: [
                'message' => $payload->like ?
                    \trans('message.like') :
                    \trans('message.unlike'),
            ],
        );
    }
}
