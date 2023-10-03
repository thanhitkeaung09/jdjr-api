<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Ping\ShowController;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\getJson;

test('returns the correct status code', function (): void {
    getJson(
        uri: action(ShowController::class)
    )->assertStatus(
        status: Http::OK->value,
    );
});

test('returns the correct payload', function (): void {
    getJson(
        uri: action(ShowController::class)
    )->assertJson(
        fn (AssertableJson $json) => $json
            ->where('message', trans('message.service.online'))
            ->etc()
    );
});
