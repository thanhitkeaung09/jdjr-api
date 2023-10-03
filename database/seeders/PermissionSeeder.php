<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;

final class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $actions = ['view', 'create', 'edit', 'delete'];

        $resources = [
            'admins' => $actions,
            'app-version' => ['edit'],
            'roles' => $actions,
            'users' => Arr::except($actions, [1]),
            'categories' => $actions,
            'subcategories' => $actions,
            'experiences' => $actions,
            'locations' => $actions,
            'jobs' => $actions,
            'news' => $actions,
            'skills' => $actions,
            'tools' => $actions,
            'levels' => $actions,
            'questions' => Arr::except($actions, [1]),
        ];

        $data = collect($resources)->map(function ($actions, $resource) {
            return collect($actions)->map(fn ($action) => ([
                'name' => "{$action}-{$resource}",
                'guard_name' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]))->toArray();
        });

        $data->push([
            [
                'name' => 'assign-roles',
                'guard_name' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'edit-admins-roles',
                'guard_name' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'edit-admins-password',
                'guard_name' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'jobs-popular',
                'guard_name' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        Permission::query()->insert($data->flatten(1)->toArray());
    }
}
