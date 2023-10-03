<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Questions;

use App\Http\Resources\V1\QuestionResource;
use App\Http\Responses\V1\ModelResponse;
use App\Models\Question;
use Illuminate\Contracts\Support\Responsable;

final readonly class ShowController
{
    public function __invoke(Question $question): Responsable
    {
        return new ModelResponse(
            data: new QuestionResource(
                resource: $question->load(['user', 'job']),
            ),
        );
    }
}
