<?php

declare(strict_types=1);

namespace App\Services\Firebase;

use Exception;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging as ContractMessaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

final class FirebaseMessaging implements Messaging
{
    private ContractMessaging $messaging;

    private CloudMessage $message;

    private array|string $tokens = [];

    public function __construct(string|array $tokens)
    {
        $this->messaging = app('firebase.messaging');

        $this->tokens = $tokens;

        if (is_string($tokens)) {
            $this->message = CloudMessage::withTarget('token', $tokens);
        } else {
            $this->message = CloudMessage::new();
        }
    }

    public static function of(string|array $tokens): self
    {
        return new self($tokens);
    }

    public function withNotification(string $title, string $body): self
    {
        $this->message = $this->message->withNotification(
            notification: Notification::create($title, $body)
        );

        return $this;
    }

    public function withData(array $data = []): self
    {
        $this->message = $this->message->withData($data);

        return $this;
    }

    public function send(): void
    {
        try {
            if (\is_string($this->tokens)) {
                $this->messaging->send($this->message);
            } else {
                $this->messaging->sendMulticast($this->message, $this->tokens);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage(),
                'debug' => $e->getTraceAsString()
            ]);
        }
    }
}
