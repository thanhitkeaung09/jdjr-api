<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Questions;

use App\Actions\V1\Questions\UpdateQuestion;
use App\Http\Requests\V1\Questions\UpsertRequest;
use App\Http\Responses\V1\MessageResponse;
use App\Models\Question;
use Illuminate\Contracts\Support\Responsable;

final readonly class UpdateController
{
    public function __construct(
        private UpdateQuestion $updateQuestion,
    ) {
    }

    public function __invoke(Question $question, UpsertRequest $request): Responsable
    {
        $status = $this->updateQuestion->handle(
            question: $question,
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
