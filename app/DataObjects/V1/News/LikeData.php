<?php

declare(strict_types=1);

namespace App\DataObjects\V1\News;

use App\Models\News;
use App\Models\User;
use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class LikeData implements DataObjectContract
{
    public function __construct(
        public User $user,
        public bool $like,
    ) {
    }

    /**
     * @param array{user:User,news:News,like:bool} $attributes
     *
     * @return LikeData
     */
    public static function of(array $attributes): LikeData
    {
        return new LikeData(
            user: $attributes['user'],
            like: $attributes['like'],
        );
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->user->id,
            'like' => $this->like,
        ];
    }
}
