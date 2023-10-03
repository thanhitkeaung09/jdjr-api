<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Permissions\IndexController;
use App\Models\Admin;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Permission;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\getJson;

test('Unauthenticated user cannot get permissions', function (): void {
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
    Sanctum::actingAs(Admin::factory()->create(), guard: 'admin');

    getJson(
        uri: action(IndexController::class),
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload', function (): void {
    Sanctum::actingAs(Admin::factory()->create(), guard: 'admin');

    /** @var Permission */
    $permission = Permission::create(['name' => 'create-user']);

    getJson(
        uri: action(IndexController::class),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->first(
                    fn (AssertableJson $json) => $json
                        ->where('id', $permission->id)
                        ->where('name', $permission->name)
                )
        );

    assertDatabaseHas('permissions', [
        'id' => $permission->id,
        'name' => $permission->name,
        'guard_name' => 'admin'
    ]);
});
