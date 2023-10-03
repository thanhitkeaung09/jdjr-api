<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Folders\StoreController;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Str;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;
use function Pest\Laravel\withHeaders;

beforeEach(function (): void {
    withAppKeyHeaders();
});

test('If there are no app keys, it is not possible to get folders', function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    Sanctum::actingAs(User::factory()->create());

    postJson(
        uri: action(StoreController::class),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

test('If the app keys are outdated, it is not possible to get folders', function (): void {
    withAppKeyHeaders(true);

    Sanctum::actingAs(User::factory()->create());

    postJson(
        uri: action(StoreController::class),
    )
        ->assertStatus(Http::UPGRADE_REQUIRED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.outdated'))
                ->where('description', \trans('message.exceptions.invalid_app_keys'))
                ->where('status', Http::UPGRADE_REQUIRED->value)
        );
});

test('Unauthenicated user cannot create folder', function (): void {
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

it('can not create folder with number input', function (): void {
    Sanctum::actingAs(User::factory()->create());

    postJson(
        uri: action(StoreController::class),
        data: [
            'name' => 12,
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('name'));
});

it('cannot create a folder with an already existing name', function (): void {
    Sanctum::actingAs($user = User::factory()->create());

    Folder::factory()->for($user)->create(['name' => 'Test']);

    postJson(
        uri: action(StoreController::class),
        data: [
            'name' => 'Test',
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('name'));
});

it('cannot create a folder with a long name', function (): void {
    Sanctum::actingAs($user = User::factory()->create());

    postJson(
        uri: action(StoreController::class),
        data: [
            'name' => Str::random(256),
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('name'));
});

it('cannot create a folder when a specified limit is exceeded', function (): void {
    Sanctum::actingAs($user = User::factory()->create());

    Folder::factory()->for($user)->count(5)->create();

    postJson(
        uri: action(StoreController::class),
        data: [
            'name' => 'Test',
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('name'));
});

it('returns the correct status code', function (): void {
    Sanctum::actingAs($user = User::factory()->create());

    postJson(
        uri: action(StoreController::class),
        data: [
            'name' => 'Test',
        ]
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload', function (): void {
    Sanctum::actingAs($user = User::factory()->create());

    postJson(
        uri: action(StoreController::class),
        data: [
            'name' => 'Test',
        ]
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('id')
                ->where('name', 'Test')
        );

    assertDatabaseCount('folders', 1);
    assertDatabaseHas('folders', ['name' => 'Test']);
});
