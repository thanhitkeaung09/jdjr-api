<?php

declare(strict_types=1);

namespace App\Actions\V1\Folders;

use App\Models\Folder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

final readonly class FetchFolder
{
    public function handle(Folder $folder): Folder
    {
        return $folder->load(['jobs' => function (MorphToMany $query): void {
            $query->orderByDesc('updated_at');
        }])->load(['news' => function (MorphToMany $query): void {
            $query->orderByDesc('updated_at');
        }]);
    }
}
