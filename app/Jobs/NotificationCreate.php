<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

final class NotificationCreate implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $type,
        public string $id,
    ) {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        User::query()->whereNotNull('device_token')->chunk(100, function (Collection $users): void {
            $data = $users->map(function ($user) {
                return [
                    'id' => (string) Str::orderedUuid(),
                    'notifiable_type' => $this->type,
                    'notifiable_id' => $this->id,
                    'user_id' => $user->id,
                    'created_at' => now()->toDateTimeString(),
                    'updated_at' => now()->toDateTimeString(),
                ];
            });

            Notification::query()->insert($data->toArray());
        });
    }
}
