<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\Firebase\FirebaseMessaging;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class SendFirebaseMessage implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string|array $deviceTokens,
        public string $id,
        public string $title,
        public string $body,
        public string $type,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        FirebaseMessaging::of($this->deviceTokens)
            ->withData([
                'type' => $this->type,
                'id' => $this->id,
                'title' => $this->title,
                'body' => $this->body,
            ])
            ->send();
    }
}
