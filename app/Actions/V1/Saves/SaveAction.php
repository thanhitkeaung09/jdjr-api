<?php

declare(strict_types=1);

namespace App\Actions\V1\Saves;

use App\DataObjects\V1\Saves\SaveData;
use App\Models\Savable;

final readonly class SaveAction
{
    public function handle(SaveData $data): Savable
    {
        $query = Savable::query()
            ->where('user_id', $data->user->id)
            ->where('folder_id', $data->folderId)
            ->where('savable_type', $data->savableType->toModelString())
            ->where('savable_id', $data->savableId);

        if (null === $query->first()) {
            return Savable::query()->create($data->toArray());
        }

        $query->delete();

        return new Savable([
            'savable_id' => null,
            'savable_type' => $data->savableType->toModelString(),
            'folder_id' => null,
            'user_id' => null,
        ]);
    }
}
