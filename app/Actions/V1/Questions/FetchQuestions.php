<?php

declare(strict_types=1);

namespace App\Actions\V1\Questions;

use App\Models\Question;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final readonly class FetchQuestions
{
    public function handle(?string $search = null): Builder
    {
        return Question::query()
            ->with(['job', 'user'])
            ->when('false' === \request('answer'), function (Builder $query): void {
                $query->whereNull('answer');
            })
            ->when($search, function (Builder $query, string $search): void {
                $query->where(DB::raw('lower(questions.question)'), 'like', '%' . mb_strtolower($search) . '%');
            })
            ->latest();
    }
}
