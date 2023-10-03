<?php

declare(strict_types=1);

namespace App\Actions\V1\News;

use App\DataObjects\V1\News\NewNews;
use App\Models\News;
use App\Services\FileStorage\FileStorage;

final readonly class UpdateNews
{
    public function __construct(
        private FileStorage $fileStorage,
    ) {
    }

    public function handle(News $news, NewNews $data): bool
    {
        $attributes = $data->toArray();

        if ($data->thumbnail) {
            $this->fileStorage->delete($news->thumbnail);

            $attributes['thumbnail'] = $this->fileStorage->upload(
                folder: \config('folders.news'),
                file: $data->thumbnail,
            );
        }

        return $news->update($attributes);
    }
}
