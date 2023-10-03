<?php

declare(strict_types=1);

namespace App\Actions\V1\Admins;

use App\DataObjects\V1\Admins\AdminData;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;

final readonly class CreateAdmin
{
    public function handle(AdminData $data): Admin|Model
    {
        /** @var Admin */
        $admin = Admin::query()->create($data->toArray());

        if ($data->roles) {
            $admin->assignRole((array) $data->roles);
        }

        return $admin;
    }
}
