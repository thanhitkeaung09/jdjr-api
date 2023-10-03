<?php

declare(strict_types=1);

namespace App\Actions\V1\Locations;

use App\Exceptions\DeleteLocationException;
use App\Models\Location;
use JustSteveKing\StatusCode\Http;

final readonly class DeleteLocation
{
    public function handle(Location $location): bool
    {
        $this->checkToDelete($location);

        return (bool) $location->delete();
    }

    private function checkToDelete(Location $location): void
    {
        if ($location->users()->count() > 0) {
            throw new DeleteLocationException(
                message: \trans('message.delete_location.users_exist'),
                code: Http::NOT_ACCEPTABLE->value,
            );
        }

        if ($location->jobs()->count() > 0) {
            throw new DeleteLocationException(
                message: \trans('message.delete_location.jobs_exist'),
                code: Http::NOT_ACCEPTABLE->value,
            );
        }
    }
}
