<?php

declare(strict_types=1);

namespace App\Services\Firebase;

interface Messaging
{
    public static function of(string|array $tokens): self;

    public function withNotification(string $title, string $body): self;

    public function withData(array $data = []): self;

    public function send(): void;
}
