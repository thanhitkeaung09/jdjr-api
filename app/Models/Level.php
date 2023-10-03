<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\LevelFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static LevelFactory factory(callable|array|int|null $count, callable|array $state)
 */
final class Level extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded = [];

    public function experiences(): HasMany
    {
        return $this->hasMany(
            related: Experience::class,
            foreignKey: 'level_id',
            localKey: 'id',
        );
    }
}
