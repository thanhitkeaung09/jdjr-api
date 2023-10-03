<?php

declare(strict_types=1);

namespace App\Actions\V1\News;

use App\Models\News;
use App\Services\FileStorage\FileStorage;
use Illuminate\Support\Facades\DB;

final readonly class DeleteNews
{
    public function __construct(
        private FileStorage $fileStorage,
    ) {
    }

    public function handle(News $news): bool
    {
        $this->fileStorage->delete($news->thumbnail);

        return DB::transaction(static function () use ($news) {
            $news->likes()->detach();
            $news->reads()->detach();
            $news->folders()->detach();
            $news->notifications()->delete();

            return $news->delete();
        });
    }
}
