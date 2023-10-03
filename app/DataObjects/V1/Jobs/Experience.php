<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Jobs;

use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class Experience implements DataObjectContract
{
    public function __construct(
        public string $position,
        public string $range,
        public string $id,
    ) {
    }

    /**
     * @param array{position:string,range:string,duration:string} $attributes
     *
     * @return Experience
     */
    public static function of(array $attributes): Experience
    {
        return new Experience(
            position: $attributes['position'],
            range: $attributes['range'],
            id: $attributes['duration'],
        );
    }

    /**
     * @return array{position_name:string,range:string}
     */
    public function toArray(): array
    {
        return [
            'position_name' => $this->position,
            'range' => $this->range,
        ];
    }
}
