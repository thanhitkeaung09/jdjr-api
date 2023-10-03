<?php

declare(strict_types=1);

namespace App\Enums;

use App\Models\Job;
use App\Models\News;

enum SavableType: string
{
    case NEWS = 'news';

    case JOB = 'job';

    public function toModelString(): string
    {
        return match ($this) {
            self::NEWS => News::class,
            self::JOB => Job::class,
        };
    }

    public static function fromModel(string $model): string
    {
        return match ($model) {
            self::NEWS->toModelString() => self::NEWS->value,
            self::JOB->toModelString() => self::JOB->value,
        };
    }

    public static function values(): array
    {
        return \array_map(
            callback: static fn (SavableType $type) => $type->value,
            array: self::cases(),
        );
    }
}
