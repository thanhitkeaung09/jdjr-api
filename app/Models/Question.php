<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\QuestionFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @method static QuestionFactory factory(callable|array|int|null $count, callable|array $state)
 */
final class Question extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'is_favourited' => 'boolean',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(
            related: Job::class,
            foreignKey: 'job_id',
            ownerKey: 'id',
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id',
            ownerKey: 'id',
        );
    }

    public function notifications(): MorphMany
    {
        return $this->morphMany(
            related: Notification::class,
            name: 'notifiable',
        );
    }
}
