<?php

declare(strict_types=1);

namespace App\Actions\V1\Notifications;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

final readonly class FetchNotifications
{
    public function handle(): Builder
    {
        return Notification::query()->with('notifiable')->where('user_id', Auth::user()->id)->latest();
    }
}
