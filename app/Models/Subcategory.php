<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\SubcategoryFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static SubcategoryFactory factory(callable|array|int|null $count, callable|array $state)
 */
final class Subcategory extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded = [];

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            related: Category::class,
            foreignKey: 'category_id',
            ownerKey: 'id',
        );
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(
            related: Job::class,
            foreignKey: 'subcategory_id',
            localKey: 'id',
        );
    }
}
