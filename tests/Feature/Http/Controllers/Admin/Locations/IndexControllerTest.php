<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Locations\IndexController;
use App\Models\Location;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\getJson;

test('Unauthenticated user cannot get locations', function (): void {
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
    superAdminLogin();

    getJson(
        uri: action(IndexController::class),
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload', function (): void {
    superAdminLogin();

    /** @var Location */
    $location = Location::factory()->create();

    getJson(
        uri: action(IndexController::class),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->hasAll(['data', 'meta', 'links'])
                ->has('data', 1)
                ->has(
                    'data.0',
                    fn (AssertableJson $json) => $json
                        ->where('id', $location->id)
                        ->where('name', $location->name)
                )
        );
});
