<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Users;

use App\Actions\V1\Users\FetchMyQuestions;
use App\Http\Resources\V1\QuestionResource;
use App\Http\Responses\V1\CollectionResponse;
use Illuminate\Contracts\Support\Responsable;

final readonly class GetQuestionsController
{
    public function __construct(
        private FetchMyQuestions $query,
    ) {
    }

    public function __invoke(): Responsable
    {
        return new CollectionResponse(
            data: QuestionResource::collection(
                resource: $this->query->handle()->get(),
            ),
            warp: 'data',
        );
    }
}
