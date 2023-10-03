<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Experiences;

use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class NewExperience implements DataObjectContract
{
    public function __construct(
        public string $duration,
        public string $level,
    ) {
    }

    /**
     * @param array{duration:string,level:string} $attributes
     *
     * @return NewExperience
     */
    public static function of(array $attributes): NewExperience
    {
        return new NewExperience(
            duration: $attributes['duration'],
            level: $attributes['level'],
        );
    }

    /**
     * @return array{duration:string,level:string}
     */
    public function toArray(): array
    {
        return [
            'duration' => $this->duration,
            'level_id' => $this->level,
        ];
    }
}
