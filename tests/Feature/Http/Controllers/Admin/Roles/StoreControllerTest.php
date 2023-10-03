<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Roles\StoreController;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\postJson;

test('Unauthenticated user cannot create role', function (): void {
    postJson(
        uri: action(StoreController::class),
    )
        ->assertStatus(Http::UNAUTHORIZED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthenicated'))
                ->where('description', 'Unauthenticated.')
                ->where('status', Http::UNAUTHORIZED->value)
        );
});

it('returns the validation error when an input name field is invalid', function ($name): void {
    superAdminLogin();

    /** @var Permission */
    $permission = Permission::create(['name' => 'create-user']);

    postJson(
        uri: action(StoreController::class),
        data: [
            'name' => $name,
            'permissions' => [$permission->id]
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('name'));
})->with('validation_names');

it('returns the validation error when an input name field is duplicated', function (): void {
    superAdminLogin();

    Role::create(['name' => 'Admin']);
    /** @var Permission */
    $permission = Permission::create(['name' => 'create-user']);

    postJson(
        uri: action(StoreController::class),
        data: [
            'name' => 'Admin',
            'permissions' => [$permission->id]
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('name'));
});

it('returns the validation error when an input permissions field is invalid', function ($permissions): void {
    superAdminLogin();

    postJson(
        uri: action(StoreController::class),
        data: [
            'name' => 'Admin',
            'permissions' => $permissions,
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('permissions'));
})->with('validation_permissions');

it('returns the validation error when ids in permissions array is invalid', function (): void {
    superAdminLogin();

    postJson(
        uri: action(StoreController::class),
        data: [
            'name' => 'Admin',
            'permissions' => [1],
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('permissions.0'));
});

it('returns the correct status code', function (): void {
    superAdminLogin();

    /** @var Permission */
    $permission = Permission::create(['name' => 'create-user']);

    postJson(
        uri: action(StoreController::class),
        data: [
            'name' => 'Admin',
            'permissions' => [$permission->id]
        ]
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload', function (): void {
    superAdminLogin();

    /** @var Permission */
    $permission = Permission::create(['name' => 'create-user']);

    postJson(
        uri: action(StoreController::class),
        data: [
            'name' => 'Admin',
            'permissions' => [$permission->id]
        ]
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('id')
                ->where('name', 'Admin')
        );

    assertDatabaseCount('roles', 2);
    assertDatabaseCount('role_has_permissions', 1);
});
