<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ApplicationKeyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @method static ApplicationKeyFactory factory(callable|array|int|null $count, callable|array $state)
 */
final class ApplicationKey extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function generateAppId(): string
    {
        return Str::uuid()->toString();
    }

    public static function generateAppSecrete(): string
    {
        return (string) (Str::uuid() . Str::uuid());
    }
}
