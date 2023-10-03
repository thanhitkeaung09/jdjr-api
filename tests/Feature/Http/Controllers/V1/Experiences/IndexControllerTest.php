<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Experiences\IndexController;
use App\Models\Experience;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\getJson;

beforeEach(function (): void {
    withAppKeyHeaders();
});

it('returns the correct status code', function (): void {
    getJson(
        uri: action(IndexController::class),
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload', function (): void {
    Experience::factory()->count(2)->create();

    getJson(
        uri: action(IndexController::class),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('data', 2)
                ->has(
                    'data.0',
                    fn (AssertableJson $json) => $json
                        ->hasAll(['id', 'duration'])
                )
        );
});
