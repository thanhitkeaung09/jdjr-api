<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Folders\UpdateController;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\putJson;
use function Pest\Laravel\withHeaders;

beforeEach(function (): void {
    withAppKeyHeaders();
});

test('If there are no app keys, it is not possible to update folder', function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    Sanctum::actingAs($user = User::factory()->create());
    $folder = Folder::factory()->for($user)->create();

    putJson(
        uri: action(UpdateController::class, ['folder' => $folder]),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

test('If the app keys are outdated, it is not possible to update folder', function (): void {
    withAppKeyHeaders(true);

    Sanctum::actingAs($user = User::factory()->create());
    $folder = Folder::factory()->for($user)->create();

    putJson(
        uri: action(UpdateController::class, ['folder' => $folder]),
    )
        ->assertStatus(Http::UPGRADE_REQUIRED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.outdated'))
                ->where('description', \trans('message.exceptions.invalid_app_keys'))
                ->where('status', Http::UPGRADE_REQUIRED->value)
        );
});

test('Unauthenicated user cannot update folder', function (): void {
    $folder = Folder::factory()->for(User::factory())->create();

    putJson(
        uri: action(UpdateController::class, ['folder' => $folder]),
    )
        ->assertStatus(Http::UNAUTHORIZED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthenicated'))
                ->where('description', 'Unauthenticated.')
                ->where('status', Http::UNAUTHORIZED->value)
        );
});

it('can not update folder with number input', function (): void {
    Sanctum::actingAs($user = User::factory()->create());

    $folder = Folder::factory()->for($user)->create();

    putJson(
        uri: action(UpdateController::class, ['folder' => $folder]),
        data: [
            'name' => 12,
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('name'));
});

it('cannot update a folder with an already existing name', function (): void {
    Sanctum::actingAs($user = User::factory()->create());

    Folder::factory()->for($user)->create(['name' => 'Test']);
    $folder = Folder::factory()->for($user)->create();

    putJson(
        uri: action(UpdateController::class, ['folder' => $folder]),
        data: [
            'name' => 'Test',
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('name'));
});

it('cannot update a folder with a long name', function (): void {
    Sanctum::actingAs($user = User::factory()->create());

    $folder = Folder::factory()->for($user)->create(['name' => 'Test']);

    putJson(
        uri: action(UpdateController::class, ['folder' => $folder]),
        data: [
            'name' => Str::random(256),
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('name'));
});

it("cannot update to other user's folder", function (): void {
    Sanctum::actingAs($user = User::factory()->create());

    Folder::factory()->for($user)->create();
    $folder = Folder::factory()->create();

    putJson(
        uri: action(UpdateController::class, ['folder' => $folder]),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

it('returns the correct status code', function (): void {
    Sanctum::actingAs($user = User::factory()->create());

    $folder = Folder::factory()->for($user)->create();

    putJson(
        uri: action(UpdateController::class, ['folder' => $folder]),
        data: [
            'name' => 'New Test',
        ]
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload', function (): void {
    Sanctum::actingAs($user = User::factory()->create());

    $folder = Folder::factory()->for($user)->create();

    putJson(
        uri: action(UpdateController::class, ['folder' => $folder]),
        data: [
            'name' => 'New Test',
        ]
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', \trans('message.update.success'))
        );

    assertDatabaseHas('folders', ['name' => 'New Test']);
});
