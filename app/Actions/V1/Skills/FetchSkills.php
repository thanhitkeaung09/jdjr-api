<?php

declare(strict_types=1);

namespace App\Actions\V1\Skills;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final readonly class FetchSkills
{
    public function handle(?string $search = null): Builder
    {
        return Skill::query()
            ->when($search, function (Builder $query, string $search): void {
                $query->where(DB::raw('lower(skills.name)'), 'like', '%' . mb_strtolower($search) . '%');
            })
            ->orderBy('name');
    }
}
