<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\AppVersionFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static AppVersionFactory factory(callable|array|int|null $count, callable|array $state)
 */
final class AppVersion extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'is_forced_updated' => 'boolean',
    ];
}
