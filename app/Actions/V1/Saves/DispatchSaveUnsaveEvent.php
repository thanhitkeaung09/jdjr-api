<?php

declare(strict_types=1);

namespace App\Actions\V1\Saves;

use App\DataObjects\V1\Saves\SaveData;
use App\Enums\SavableType;
use App\Events\JobSaveEvent;
use App\Events\NewsSaveEvent;
use App\Models\Savable;

final readonly class DispatchSaveUnsaveEvent
{
    public function handle(SaveData $data, Savable|null $savable): void
    {
        if (SavableType::NEWS === $data->savableType) {
            event(new NewsSaveEvent($data->savableId, null !== $savable, $savable?->folder_id));
        } else {
            event(new JobSaveEvent($data->savableId, null !== $savable, $savable?->folder_id));
        }
    }
}
