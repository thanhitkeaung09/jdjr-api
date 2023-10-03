<?php

declare(strict_types=1);

namespace App\Actions\V1\News;

use App\Models\News;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

final readonly class MakeRead
{
    public function handle(News $news): void
    {
        /** @var User */
        $user = Auth::user();

        if ($user->readedNews()->wherePivot('news_id', $news->id)->exists()) {
            $user->readedNews()->detach($news->id);
            $user->readedNews()->attach($news->id);
        } else {
            $user->readedNews()->attach($news->id);
        }
    }
}
