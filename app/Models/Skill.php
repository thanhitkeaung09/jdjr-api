<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\SkillFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static SkillFactory factory(callable|array|int|null $count, callable|array $state)
 */
final class Skill extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded = [];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            related: User::class,
            table: 'users_skills',
            foreignPivotKey: 'skill_id',
            relatedPivotKey: 'user_id',
        );
    }

    public function jobs(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Job::class,
            table: 'jobs_skills',
            foreignPivotKey: 'skill_id',
            relatedPivotKey: 'job_id',
        );
    }
}
