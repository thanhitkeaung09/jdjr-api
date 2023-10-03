<?php

declare(strict_types=1);

namespace App\Actions\V1\Notifications;

use App\Models\Notification;

final readonly class ReadNotification
{
    public function handle(Notification $notification): bool
    {
        return $notification->update(['is_readed' => true]);
    }
}
