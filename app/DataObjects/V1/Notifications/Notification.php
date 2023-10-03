<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Notifications;

use App\Enums\NotificationType;
use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class Notification implements DataObjectContract
{
    public function __construct(
        public string $id,
        public string $title,
        public string $body,
        public NotificationType $type,
    ) {
    }

    /**
     * @param array{id:string,title:string,body:string,type:NotificationType} $attributes
     *
     * @return Notification
     */
    public static function of(array $attributes): Notification
    {
        return new Notification(
            id: $attributes['id'],
            title: $attributes['title'],
            body: $attributes['body'],
            type: $attributes['type'],
        );
    }

    /**
     * @return array{id:string,title:string,body:string,type:string}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'type' => $this->type->value,
        ];
    }
}
