<?php

declare(strict_types=1);

namespace App\Actions\V1\Experiences;

use App\DataObjects\V1\Experiences\NewExperience;
use App\Models\Experience;

final readonly class UpdateExperience
{
    public function handle(Experience $experience, NewExperience $data): bool
    {
        return $experience->update($data->toArray());
    }
}
