<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Roles\ShowController;
use App\Models\Admin;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\getJson;

test('Unauthenticated user cannot show role', function (): void {
    $role = Role::create(['name' => 'Admin']);

    getJson(
        uri: action(ShowController::class, ['role' => $role]),
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
    Sanctum::actingAs(Admin::factory()->create(), guard: 'admin');

    $role = Role::create(['name' => 'Admin']);

    getJson(
        uri: action(ShowController::class, ['role' => $role]),
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload', function (): void {
    Sanctum::actingAs(Admin::factory()->create(), guard: 'admin');

    /** @var Role */
    $role = Role::create(['name' => 'Admin']);
    /** @var Permission */
    $permission = Permission::create(['name' => 'create-user']);

    $role->syncPermissions($permission);

    getJson(
        uri: action(ShowController::class, ['role' => $role]),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
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
        );
});
