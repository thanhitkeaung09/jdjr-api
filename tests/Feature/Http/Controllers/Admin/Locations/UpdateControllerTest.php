<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Locations\UpdateController;
use App\Models\Location;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\putJson;

test('Unauthenticated user cannot update location', function (): void {
    /** @var Location */
    $location = Location::factory()->create();

    putJson(
        uri: action(UpdateController::class, ['location' => $location]),
    )
        ->assertStatus(Http::UNAUTHORIZED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthenicated'))
                ->where('description', 'Unauthenticated.')
                ->where('status', Http::UNAUTHORIZED->value)
        );
});

it('returns the not found status code when a resource is not found', function (): void {
    superAdminLogin();

    putJson(
        uri: action(UpdateController::class, ['location' => 'djfkdjfkjkwekwjekw']),
    )
        ->assertStatus(Http::NOT_FOUND->value);
});

it('returns the validation error when an input name field is invalid', function ($name): void {
    superAdminLogin();

    $location = Location::factory()->create();

    putJson(
        uri: action(UpdateController::class, ['location' => $location]),
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
    $location = Location::factory()->create();

    putJson(
        uri: action(UpdateController::class, ['location' => $location]),
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

    /** @var Location */
    $location = Location::factory()->create();

    putJson(
        uri: action(UpdateController::class, ['location' => $location]),
        data: ['name' => 'New Location']
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload', function (): void {
    superAdminLogin();

    /** @var Location */
    $location = Location::factory()->create();

    putJson(
        uri: action(UpdateController::class, ['location' => $location]),
        data: ['name' => 'New Location'],
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', \trans('message.update.success'))
        );

    assertDatabaseHas('locations', ['name' => 'New Location']);
});
