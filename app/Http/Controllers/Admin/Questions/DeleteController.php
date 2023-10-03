<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Questions;

use App\Actions\V1\Questions\DeleteQuestion;
use App\Http\Responses\V1\MessageResponse;
use App\Models\Question;
use Illuminate\Contracts\Support\Responsable;

final readonly class DeleteController
{
    public function __construct(
        private DeleteQuestion $deleteQuestion,
    ) {
    }

    public function __invoke(Question $question): Responsable
    {
        $status = $this->deleteQuestion->handle($question);

        return new MessageResponse(
            data: [
                'message' => $status ?
                    \trans('message.delete.success') :
                    \trans('message.delete.fail'),
            ],
        );
    }
}
