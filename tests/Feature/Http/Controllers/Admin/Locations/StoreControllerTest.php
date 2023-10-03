<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Locations\StoreController;
use App\Models\Location;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

test('Unauthenticated user cannot create location', function (): void {
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

    postJson(
        uri: action(StoreController::class),
        data: [
            'name' => $name,
        ],
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('name'));
})->with('validation_names');

it('returns the validation error when an input name field is duplicated', function (): void {
    superAdminLogin();

    Location::factory()->create(['name' => 'Yangon']);

    postJson(
        uri: action(StoreController::class),
        data: [
            'name' => 'Yangon',
        ],
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('name'));
});

it('returns the correct status code', function (): void {
    superAdminLogin();

    postJson(
        uri: action(StoreController::class),
        data: ['name' => 'Yangon']
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload', function (): void {
    superAdminLogin();

    postJson(
        uri: action(StoreController::class),
        data: ['name' => 'Yangon']
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('id')
                ->where('name', 'Yangon')
        );

    assertDatabaseHas('locations', ['name' => 'Yangon']);
});
