<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Skills;

use Illuminate\Http\UploadedFile;
use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class NewSkill implements DataObjectContract
{
    public function __construct(
        public string $name,
        public UploadedFile|null $icon,
    ) {
    }

    /**
     * @param array{name:string,icon?:UploadedFile} $attributes
     *
     * @return NewSkill
     */
    public static function of(array $attributes): NewSkill
    {
        return new NewSkill(
            name: $attributes['name'],
            icon: $attributes['icon'],
        );
    }

    /**
     * @return array{name:string}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
