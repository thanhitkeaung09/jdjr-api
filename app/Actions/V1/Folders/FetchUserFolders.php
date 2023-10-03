<?php

declare(strict_types=1);

namespace App\Actions\V1\Folders;

use App\Models\Folder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

final readonly class FetchUserFolders
{
    public function handle(): Builder
    {
        return Folder::query()
            ->where('user_id', Auth::user()->id)
            ->orderBy('name');
    }
}
