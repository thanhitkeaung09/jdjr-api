<?php

declare(strict_types=1);

namespace App\Actions\V1\Skills;

use App\DataObjects\V1\Categories\CategoryIds;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Builder;

final readonly class FetchSkillsByCategories
{
    public function handle(CategoryIds $data): Builder
    {
        return Skill::query()
            ->whereHas('jobs.subcategory', function (Builder $query) use ($data): void {
                $query->whereIn('category_id', $data->toArray());
            })
            ->orderBy('name');
    }
}
