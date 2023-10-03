<?php

declare(strict_types=1);

namespace App\Enums;

use App\Models\Job;
use App\Models\News;
use App\Models\Question;

enum NotificationType: string
{
    case NEWS = 'news';

    case JOB = 'job';

    case QUESTION = 'question';

    public function toModelString(): string
    {
        return match ($this) {
            self::NEWS => News::class,
            self::JOB => Job::class,
            self::QUESTION => Question::class,
        };
    }

    public static function fromModel(string $model): string
    {
        return match ($model) {
            self::NEWS->toModelString() => self::NEWS->value,
            self::JOB->toModelString() => self::JOB->value,
            self::QUESTION->toModelString() => self::QUESTION->value,
        };
    }

    public static function values(): array
    {
        return \array_map(
            callback: static fn (NotificationType $type) => $type->value,
            array: self::cases(),
        );
    }
}
