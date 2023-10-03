<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\UserBuilder;
use App\Enums\Language;
use App\Enums\LoginType;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method static UserFactory factory(callable|array|int|null $count, callable|array $state)
 * @method static UserFactory factory(callable|array|int|null $count, callable|array $state)
 */
final class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasUuids;
    use Notifiable;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'login_type' => LoginType::class,
        'language' => Language::class,
    ];

    protected $perPage = 20;

    public function revokeTokens(): void
    {
        $this->tokens()->delete();
    }

    public static function query(): UserBuilder
    {
        /** @var UserBuilder */
        return parent::query();
    }

    public function newEloquentBuilder($query): UserBuilder
    {
        return new UserBuilder($query);
    }

    public function experience(): BelongsTo
    {
        return $this->belongsTo(
            related: Experience::class,
            foreignKey: 'experience_id',
            ownerKey: 'id'
        );
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(
            related: Location::class,
            foreignKey: 'location_id',
            ownerKey: 'id'
        );
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(
            related: Job::class,
            foreignKey: 'current_position',
            ownerKey: 'id',
        );
    }

    public function interests(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Category::class,
            table: 'users_interests',
            foreignPivotKey: 'user_id',
            relatedPivotKey: 'category_id',
        );
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Skill::class,
            table: 'users_skills',
            foreignPivotKey: 'user_id',
            relatedPivotKey: 'skill_id',
        );
    }

    public function folders(): HasMany
    {
        return $this->hasMany(
            related: Folder::class,
            foreignKey: 'user_id',
            localKey: 'id',
        );
    }

    /**
     * Get all the news that is liked by this user.
     */
    public function likedNews(): BelongsToMany
    {
        return $this->belongsToMany(
            related: News::class,
            table: 'likes',
            foreignPivotKey: 'user_id',
            relatedPivotKey: 'news_id',
        )
            ->withTimestamps();
    }

    /**
     * Get all the news that is readed by this user.
     */
    public function readedNews(): BelongsToMany
    {
        return $this->belongsToMany(
            related: News::class,
            table: 'reads',
            foreignPivotKey: 'user_id',
            relatedPivotKey: 'news_id',
        )
            ->withTimestamps();
    }

    public function questions(): HasMany
    {
        return $this->hasMany(
            related: Question::class,
            foreignKey: 'user_id',
            localKey: 'id',
        );
    }
}
