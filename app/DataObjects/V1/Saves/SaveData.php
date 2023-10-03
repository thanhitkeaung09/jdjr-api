<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Saves;

use App\Enums\SavableType;
use App\Models\User;
use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class SaveData implements DataObjectContract
{
    public function __construct(
        public string $folderId,
        public SavableType $savableType,
        public string $savableId,
        public User $user,
    ) {
    }

    /**
     * @param array{folder_id:string,savable_type:SavableType,savable_id:string,user:User} $attributes
     *
     * @return SaveData
     */
    public static function of(array $attributes): SaveData
    {
        return new SaveData(
            folderId: $attributes['folder_id'],
            savableType: $attributes['savable_type'],
            savableId: $attributes['savable_id'],
            user: $attributes['user'],
        );
    }

    /**
     * @return array{folder_id:string,savable_type:string,savable_id:string,user_id:string}
     */
    public function toArray(): array
    {
        return [
            'folder_id' => $this->folderId,
            'savable_type' => $this->savableType->toModelString(),
            'savable_id' => $this->savableId,
            'user_id' => $this->user->id,
        ];
    }
}
