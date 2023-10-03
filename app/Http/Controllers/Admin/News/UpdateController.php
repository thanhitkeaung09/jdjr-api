<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\News;

use App\Actions\V1\News\UpdateNews;
use App\Http\Requests\V1\News\UpsertRequest;
use App\Http\Responses\V1\MessageResponse;
use App\Models\News;
use Illuminate\Contracts\Support\Responsable;

final readonly class UpdateController
{
    public function __construct(
        private UpdateNews $updateNews,
    ) {
    }

    public function __invoke(News $news, UpsertRequest $request): Responsable
    {
        $status = $this->updateNews->handle(
            news: $news,
            data: $request->payload(),
        );

        return new MessageResponse(
            data: [
                'message' => $status ?
                    \trans('message.update.success') :
                    \trans('message.update.fail'),
            ],
        );
    }
}
