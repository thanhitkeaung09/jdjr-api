<?php

declare(strict_types=1);

namespace App\Actions\V1\News;

use App\Models\News;

final readonly class FetchNews
{
    public function handle(News $news): News
    {
        return $news->loadCount('likes');
    }
}
