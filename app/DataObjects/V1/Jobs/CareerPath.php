<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Jobs;

use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class CareerPath implements DataObjectContract
{
    public function __construct(
        public string $title,
        public string $body,
    ) {
    }

    /**
     * @param array{title:string,body:string} $attributes
     *
     * @return CareerPath
     */
    public static function of(array $attributes): CareerPath
    {
        return new CareerPath(
            title: $attributes['title'],
            body: $attributes['body'],
        );
    }

    /**
     * @return array{title:string,body:string}
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
        ];
    }
}
