<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\JobFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Auth;

/**
 * @method static JobFactory factory(callable|array|int|null $count, callable|array $state)
 */
final class Job extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded = [];

    public function users(): HasMany
    {
        return $this->hasMany(
            related: User::class,
            foreignKey: 'current_position',
            localKey: 'id',
        );
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(
            related: Subcategory::class,
            foreignKey: 'subcategory_id',
            ownerKey: 'id',
        );
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(
            related: Location::class,
            foreignKey: 'location_id',
            ownerKey: 'id',
        );
    }

    public function description(): HasOne
    {
        return $this->hasOne(
            related: Description::class,
            foreignKey: 'job_id',
            localKey: 'id',
        );
    }

    public function questions(): HasMany
    {
        return $this->hasMany(
            related: Question::class,
            foreignKey: 'job_id',
            localKey: 'id',
        );
    }

    public function qualifications(): HasMany
    {
        return $this->hasMany(
            related: Qualification::class,
            foreignKey: 'job_id',
            localKey: 'id',
        );
    }

    public function responsibilities(): HasMany
    {
        return $this->hasMany(
            related: Responsibility::class,
            foreignKey: 'job_id',
            localKey: 'id',
        );
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Skill::class,
            table: 'jobs_skills',
            foreignPivotKey: 'job_id',
            relatedPivotKey: 'skill_id',
        );
    }

    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Tool::class,
            table: 'jobs_tools',
            foreignPivotKey: 'job_id',
            relatedPivotKey: 'tool_id',
        );
    }

    public function careerPaths(): HasMany
    {
        return $this->hasMany(
            related: CareerPath::class,
            foreignKey: 'job_id',
            localKey: 'id',
        );
    }

    public function experiences(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Experience::class,
            table: 'jobs_experiences',
            foreignPivotKey: 'job_id',
            relatedPivotKey: 'experience_id',
        )
            ->as('salary')
            ->withPivot(
                columns: ['position_name', 'range']
            );
    }

    /**
     * Get all of the saved job folders.
     */
    public function folders(): MorphToMany
    {
        return $this->morphToMany(
            related: Folder::class,
            name: 'savable'
        )
            ->withPivot(
                columns: ['user_id',]
            )
            ->withTimestamps()
            ->as('savable')
            ->using(Savable::class);
    }

    public function currentUserSavedFolders(): BelongsToMany
    {
        return $this->folders()->wherePivot('user_id', Auth::user()->id);
    }

    public function notifications(): MorphMany
    {
        return $this->morphMany(
            related: Notification::class,
            name: 'notifiable',
        );
    }

    public function popular(): HasOne
    {
        return $this->hasOne(
            related: PopularSearch::class,
            foreignKey: 'job_id',
            localKey: 'id',
        );
    }
}
