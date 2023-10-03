<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Roles\DeleteController;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\deleteJson;

test('Unauthenticated user cannot delete role', function (): void {
    /** @var Role */
    $role = Role::create(['name' => 'Admin']);

    deleteJson(
        uri: action(DeleteController::class, ['role' => $role]),
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
    superAdminLogin();

    /** @var Role */
    $role = Role::create(['name' => 'Admin']);

    deleteJson(
        uri: action(DeleteController::class, ['role' => $role]),
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload', function (): void {
    superAdminLogin();

    /** @var Role */
    $role = Role::create(['name' => 'Admin']);
    /** @var Permission */
    $permission = Permission::create(['name' => 'create-user']);

    $role->givePermissionTo($permission);

    deleteJson(
        uri: action(DeleteController::class, ['role' => $role]),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', \trans('message.delete.success'))
        );

    assertDatabaseCount('roles', 1);
    assertDatabaseCount('role_has_permissions', 0);
});
