<?php

declare(strict_types=1);

namespace App\Actions\V1\Experiences;

use App\Models\Experience;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final readonly class FetchExperiences
{
    public function handle(?string $search = null): Builder
    {
        return Experience::query()
            ->when($search, function (Builder $query, string $search): void {
                $query->where(DB::raw('lower(experiences.duration)'), 'like', '%' . mb_strtolower($search) . '%');
            })
            ->orderBy('duration');
    }
}
