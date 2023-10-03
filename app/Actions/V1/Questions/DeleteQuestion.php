<?php

declare(strict_types=1);

namespace App\Actions\V1\Questions;

use App\Models\Question;

final readonly class DeleteQuestion
{
    public function handle(Question $question): bool
    {
        $question->notifications()->delete();

        return $question->delete();
    }
}
