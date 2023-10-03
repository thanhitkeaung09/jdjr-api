<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\FolderFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @method static FolderFactory factory(callable|array|int|null $count, callable|array $state)
 */
final class Folder extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            foreignKey: 'user_id',
            ownerKey: 'id',
        );
    }

    /**
     * Get all of the jobs in this folder.
     */
    public function jobs(): MorphToMany
    {
        return $this->morphedByMany(
            related: Job::class,
            name: 'savable',
        )
            ->withPivot(
                columns: ['user_id',]
            )
            ->withTimestamps()
            ->as('savable')
            ->using(Savable::class);
    }

    /**
     * Get all of the news in this folder.
     */
    public function news(): MorphToMany
    {
        return $this->morphedByMany(
            related: News::class,
            name: 'savable',
        )
            ->withPivot(
                columns: ['user_id',]
            )
            ->withTimestamps()
            ->as('savable')
            ->using(Savable::class);
    }
}
