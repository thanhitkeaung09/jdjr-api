<?php

declare(strict_types=1);

namespace App\Actions\V1\Experiences;

use App\DataObjects\V1\Experiences\NewExperience;
use App\Models\Experience;

final readonly class CreateExperience
{
    public function handle(NewExperience $data): Experience
    {
        return Experience::query()->create($data->toArray());
    }
}
