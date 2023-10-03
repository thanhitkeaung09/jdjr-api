<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\DescriptionFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static DescriptionFactory factory(callable|array|int|null $count, callable|array $state)
 */
final class Description extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded =  [];

    public function job(): BelongsTo
    {
        return $this->belongsTo(
            related: Job::class,
            foreignKey: 'job_id',
            ownerKey: 'id',
        );
    }
}
