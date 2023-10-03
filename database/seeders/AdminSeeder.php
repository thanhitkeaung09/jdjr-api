<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Super Admin',
                'email' => 'jdjr.superadmin@gmail.com',
                'password' => Hash::make('superadmin@1359#'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin',
                'email' => 'jdjr.admin@gmail.com',
                'password' => Hash::make('admin@2468#'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Admin::query()->insert($data);
    }
}
