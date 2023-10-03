<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Locations\ShowController;
use App\Models\Admin;
use App\Models\Location;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;

test('Unauthenticated user cannot show location', function (): void {
    /** @var Location */
    $location = Location::factory()->create();

    getJson(
        uri: action(ShowController::class, ['location' => $location]),
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
    Sanctum::actingAs(Admin::factory()->create(), guard: 'admin');

    getJson(
        uri: action(ShowController::class, ['location' => 'djfkdjfkjkwekwjekw']),
    )
        ->assertStatus(Http::NOT_FOUND->value);
});

it('returns the correct status code', function (): void {
    Sanctum::actingAs(Admin::factory()->create(), guard: 'admin');

    /** @var Location */
    $location = Location::factory()->create();

    getJson(
        uri: action(ShowController::class, ['location' => $location]),
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload', function (): void {
    Sanctum::actingAs(Admin::factory()->create(), guard: 'admin');

    /** @var Location */
    $location = Location::factory()->create();

    getJson(
        uri: action(ShowController::class, ['location' => $location]),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('id', $location->id)
                ->where('name', $location->name)
        );
});
