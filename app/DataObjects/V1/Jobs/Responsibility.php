<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Jobs;

use Illuminate\Http\UploadedFile;
use Thuraaung\MakeFiles\Contracts\DataObjectContract;
use Illuminate\Support\Str;

use function Safe\parse_url;

final readonly class Responsibility implements DataObjectContract
{
    public function __construct(
        public string $text,
        public UploadedFile|string $icon,
    ) {
    }

    /**
     * @param array{text:string,icon?:UploadedFile,old_icon:string} $attributes
     *
     * @return Responsibility
     */
    public static function of(array $attributes): Responsibility
    {
        return new Responsibility(
            text: $attributes['text'],
            icon: array_key_exists('icon', $attributes) ?
                $attributes['icon'] : static::getPath($attributes['old_icon']),
        );
    }

    private static function getPath(string $url): string
    {
        $path = parse_url($url)['path'];

        return Str::replace('/api/v1/images/', '', $path);
    }

    /**
     * @return array{text:string}
     */
    public function toArray(): array
    {
        return [
            'text' => $this->text,
        ];
    }
}
