<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Questions;

use App\Actions\V1\Questions\CreateQuestion;
use App\Http\Requests\V1\Questions\UpsertRequest;
use App\Http\Resources\V1\QuestionResource;
use App\Http\Responses\V1\ModelResponse;
use Illuminate\Contracts\Support\Responsable;

final readonly class StoreController
{
    public function __construct(
        private CreateQuestion $createQuestion,
    ) {
    }

    public function __invoke(UpsertRequest $request): Responsable
    {
        return new ModelResponse(
            data: new QuestionResource(
                resource: $this->createQuestion->handle($request->payload())
            ),
        );
    }
}
