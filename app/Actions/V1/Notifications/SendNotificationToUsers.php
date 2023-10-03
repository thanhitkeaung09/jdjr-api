<?php

declare(strict_types=1);

namespace App\Actions\V1\Notifications;

use App\DataObjects\V1\Notifications\Notification;
use App\Jobs\SendFirebaseMessage;
use App\Models\User;

final readonly class SendNotificationToUsers
{
    public function handle(Notification $notification): void
    {
        User::query()
            ->whereNotNull('device_token')
            ->chunk(400, function ($users) use ($notification): void {
                SendFirebaseMessage::dispatch(
                    $users->pluck('device_token')->all(),
                    ...$notification->toArray(),
                );
            });
    }
}
