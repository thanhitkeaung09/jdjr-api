<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Folders;

use App\Models\User;
use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class NewFolderData implements DataObjectContract
{
    public function __construct(
        public string $name,
        public User $user,
    ) {
    }

    /**
     * @param array{name:string,user:User} $attributes
     */
    public static function of(array $attributes): NewFolderData
    {
        return new NewFolderData(
            name: $attributes['name'],
            user: $attributes['user']
        );
    }

    /**
     * @return array{name:string,user_id:int}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'user_id' => $this->user->id,
        ];
    }
}
