<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ApplicationKey;
use Illuminate\Database\Seeder;

final class ApplicationKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ApplicationKey::query()->create([
            'name' => 'API Version 1',
            'app_id' => ApplicationKey::generateAppId(),
            'app_secrete' => ApplicationKey::generateAppSecrete(),
        ]);
    }
}
