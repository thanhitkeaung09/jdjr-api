<?php

declare(strict_types=1);

namespace App\Actions\V1\AppVersions;

use App\DataObjects\V1\AppVersions\AppVersionData;
use App\Models\AppVersion;

final readonly class UpdateAppVersion
{
    public function handle(AppVersion $appVersion, AppVersionData $data): bool
    {
        return $appVersion->update($data->toArray());
    }
}
