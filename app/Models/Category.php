<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * @method static CategoryFactory factory(callable|array|int|null $count, callable|array $state)
 */
final class Category extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded = [];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            related: User::class,
            table: 'users_interests',
            foreignPivotKey: 'category_id',
            relatedPivotKey: 'user_id',
        );
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(
            related: Subcategory::class,
            foreignKey: 'category_id',
            localKey: 'id',
        );
    }

    public function jobs(): HasManyThrough
    {
        return $this->hasManyThrough(
            related: Job::class,
            through: Subcategory::class,
        );
    }
}
