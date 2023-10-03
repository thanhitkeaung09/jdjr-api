<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ToolFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static ToolFactory factory(callable|array|int|null $count, callable|array $state)
 */
final class Tool extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded = [];

    public function jobs(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Job::class,
            table: 'jobs_tools',
            foreignPivotKey: 'tool_id',
            relatedPivotKey: 'job_id',
        );
    }
}
