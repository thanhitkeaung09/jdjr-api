<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Locations\DeleteController;
use App\Models\Location;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\deleteJson;

test('Unauthenticated user cannot delete locations', function (): void {
    $location = Location::factory()->create();

    deleteJson(
        uri: action(DeleteController::class, ['location' => $location]),
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

    $location = Location::factory()->create();

    deleteJson(
        uri: action(DeleteController::class, ['location' => $location]),
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload', function (): void {
    superAdminLogin();

    $location = Location::factory()->create();

    deleteJson(
        uri: action(DeleteController::class, ['location' => $location]),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', \trans('message.delete.success'))
        );

    assertDatabaseCount('locations', 0);
});
