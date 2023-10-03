<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\NewsFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Auth;

/**
 * @method static NewsFactory factory(callable|array|int|null $count, callable|array $state)
 */
final class News extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded = [];

    /**
     * Get all the liked users of this news.
     */
    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(
            related: User::class,
            table: 'likes',
            foreignPivotKey: 'news_id',
            relatedPivotKey: 'user_id',
        )
            ->withTimestamps();
    }

    /**
     * Get all the readed users of this news.
     */
    public function reads(): BelongsToMany
    {
        return $this->belongsToMany(
            related: User::class,
            table: 'reads',
            foreignPivotKey: 'news_id',
            relatedPivotKey: 'user_id',
        )
            ->withTimestamps();
    }

    /**
     * Get all the saved folders of this news.
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

    public function currentUserLikes(): BelongsToMany
    {
        return $this->likes()->wherePivot('user_id', Auth::user()->id);
    }

    public function notifications(): MorphMany
    {
        return $this->morphMany(
            related: Notification::class,
            name: 'notifiable',
        );
    }
}
