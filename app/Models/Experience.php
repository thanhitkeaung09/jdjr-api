<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ExperienceFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static ExperienceFactory factory(callable|array|int|null $count, callable|array $state)
 */
final class Experience extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded = [];

    public function users(): HasMany
    {
        return $this->hasMany(
            related: User::class,
            foreignKey: 'experience_id',
            localKey: 'id',
        );
    }

    public function jobs(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Job::class,
            table: 'jobs_experiences',
            foreignPivotKey: 'experience_id',
            relatedPivotKey: 'job_id',
        );
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(
            related: Level::class,
            foreignKey: 'level_id',
            ownerKey: 'id',
        );
    }
}
