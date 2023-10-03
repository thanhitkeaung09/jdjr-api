<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\LocationFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static LocationFactory factory(callable|array|int|null $count, callable|array $state)
 */
final class Location extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded = [];

    public function users(): HasMany
    {
        return $this->hasMany(
            related: User::class,
            foreignKey: 'location_id',
            localKey: 'id',
        );
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(
            related: Job::class,
            foreignKey: 'location_id',
            localKey: 'id',
        );
    }
}
