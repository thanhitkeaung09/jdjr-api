<?php

declare(strict_types=1);

namespace App\DataObjects\V1\News;

use Illuminate\Http\UploadedFile;
use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class NewNews implements DataObjectContract
{
    public function __construct(
        public string $title,
        public string $shortBody,
        public string $body,
        public UploadedFile|null $thumbnail,
    ) {
    }

    /**
     * @param array{title:string,short_body:string,body:string,thumbnail?:UploadedFile} $attributes
     *
     * @return NewNews
     */
    public static function of(array $attributes): NewNews
    {
        return new NewNews(
            title: $attributes['title'],
            shortBody: $attributes['short_body'],
            body: $attributes['body'],
            thumbnail: $attributes['thumbnail'],
        );
    }

    /**
     * @return array{title:string,short_body:string,body:string}
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'short_body' => $this->shortBody,
            'body' => $this->body,
        ];
    }
}
