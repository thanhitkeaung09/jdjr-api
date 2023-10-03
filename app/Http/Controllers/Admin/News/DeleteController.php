<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\News;

use App\Actions\V1\News\DeleteNews;
use App\Http\Responses\V1\MessageResponse;
use App\Models\News;
use Illuminate\Contracts\Support\Responsable;

final readonly class DeleteController
{
    public function __construct(
        private DeleteNews $deleteNews,
    ) {
    }

    public function __invoke(News $news): Responsable
    {
        $status = $this->deleteNews->handle($news);

        return new MessageResponse(
            data: [
                'message' => $status ?
                    \trans('message.delete.success') :
                    \trans('message.delete.fail'),
            ],
        );
    }
}
