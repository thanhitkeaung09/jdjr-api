<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Roles\IndexController;
use App\Models\Admin;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\getJson;

test('Unauthenticated user cannot get roles', function (): void {
    getJson(
        uri: action(IndexController::class),
    )
        ->assertStatus(Http::UNAUTHORIZED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthenicated'))
                ->where('description', 'Unauthenticated.')
                ->where('status', Http::UNAUTHORIZED->value)
        );
});

it('returns the correct status code', function (): void {
    $admin = Admin::factory()->create();
    $admin->assignRole(Role::create(['name' => 'Super Admin', 'guard_name' => 'admin']));
    Sanctum::actingAs($admin, guard: 'admin');

    getJson(
        uri: action(IndexController::class),
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload when type is all', function (): void {
    /** @var Role */
    $role = Role::create(['name' => 'Admin', 'guard_name' => 'admin']);
    /** @var Permission */
    $permission = Permission::create(['name' => 'view-roles', 'guard_name' => 'admin']);
    $role->syncPermissions($permission);

    $admin = Admin::factory()->create();
    $admin->assignRole($role);

    Sanctum::actingAs($admin, guard: 'admin');

    getJson(
        uri: action(IndexController::class, ['type' => 'all']),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->first(
                    fn (AssertableJson $json) => $json
                        ->where('id', $role->id)
                        ->where('name', $role->name)
                        ->whereType('permissions', 'array')
                        ->has(
                            'permissions.0',
                            fn (AssertableJson $json) => $json
                                ->where('id', $permission->id)
                                ->where('name', $permission->name)
                        )
                )
        );
});



it('returns the correct payload', function (): void {
    /** @var Role */
    $role = Role::create(['name' => 'Admin', 'guard_name' => 'admin']);
    /** @var Permission */
    $permission = Permission::create(['name' => 'view-roles', 'guard_name' => 'admin']);
    $role->syncPermissions($permission);

    $admin = Admin::factory()->create();
    $admin->assignRole($role);

    Sanctum::actingAs($admin, guard: 'admin');

    getJson(
        uri: action(IndexController::class),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has(
                    'data',
                    fn (AssertableJson $json) => $json
                        ->first(
                            fn (AssertableJson $json) => $json
                                ->where('id', $role->id)
                                ->where('name', $role->name)
                                ->whereType('permissions', 'array')
                                ->has(
                                    'permissions.0',
                                    fn (AssertableJson $json) => $json
                                        ->where('id', $permission->id)
                                        ->where('name', $permission->name)
                                )
                        ),
                )
                ->hasAll(['meta', 'links']),
        );
});
