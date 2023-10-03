<?php

declare(strict_types=1);

namespace App\Actions\V1\AppVersions;

use App\Models\AppVersion;
use Illuminate\Database\Eloquent\Builder;

final readonly class FetchAppVersions
{
    public function handle(): Builder
    {
        return AppVersion::query();
    }
}
