<?php

declare(strict_types=1);

namespace App\Actions\V1\News;

use App\Actions\V1\Notifications\SendNotificationToUsers;
use App\DataObjects\V1\News\NewNews;
use App\DataObjects\V1\Notifications\Notification;
use App\Enums\NotificationType;
use App\Jobs\NotificationCreate;
use App\Models\News;
use App\Services\FileStorage\FileStorage;

final readonly class CreateNews
{
    public function __construct(
        private FileStorage $fileStorage,
        private SendNotificationToUsers $sendNotificationToUsers,
    ) {
    }
    public function handle(NewNews $data): News
    {
        $path = $this->fileStorage->upload(
            folder: \config('folders.news'),
            file: $data->thumbnail,
        );

        $news = News::query()->create(
            attributes: [
                ...$data->toArray(),
                'thumbnail' => $path,
            ],
        );

        $this->sendNotifications($news);

        return $news;
    }

    private function sendNotifications(News $news): void
    {
        NotificationCreate::dispatch(
            type: NotificationType::NEWS->toModelString(),
            id: $news->id,
        );

        $this->sendNotificationToUsers->handle(
            notification: Notification::of([
                'id' => $news->id,
                'title' => $news->title,
                'body' => $news->short_body,
                'type' => NotificationType::NEWS,
            ]),
        );
    }
}
