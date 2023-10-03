<?php

declare(strict_types=1);

namespace App\Actions\V1\Questions;

use App\DataObjects\V1\Questions\NewQuestion;
use App\Events\NewQuestionEvent;
use App\Models\Question;

final readonly class CreateQuestion
{
    public function handle(NewQuestion $data): Question
    {
        $question = Question::query()->create($data->toArray());

        event(new NewQuestionEvent($question));

        return $question;
    }
}
