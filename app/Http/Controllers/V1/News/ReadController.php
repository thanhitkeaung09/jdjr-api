<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\News;

use App\Actions\V1\News\MakeRead;
use App\Http\Responses\V1\MessageResponse;
use App\Models\News;
use Illuminate\Contracts\Support\Responsable;

final readonly class ReadController
{
    public function __construct(
        private MakeRead $makeRead,
    ) {
    }

    public function __invoke(News $news): Responsable
    {
        $this->makeRead->handle($news);

        return new MessageResponse(
            data: [
                'message' => \trans('message.read.success'),
            ],
        );
    }
}
