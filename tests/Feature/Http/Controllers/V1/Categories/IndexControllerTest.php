<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Categories\IndexController;
use App\Models\Category;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\getJson;
use function Pest\Laravel\withHeaders;

beforeEach(function (): void {
    withAppKeyHeaders();
});

test('If there are no app keys, it is not possible to get categories', function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    getJson(
        uri: action(IndexController::class),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

test('If the app keys are outdated, it is not possible to get categories', function (): void {
    withAppKeyHeaders(true);

    getJson(
        uri: action(IndexController::class),
    )
        ->assertStatus(Http::UPGRADE_REQUIRED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.outdated'))
                ->where('description', \trans('message.exceptions.invalid_app_keys'))
                ->where('status', Http::UPGRADE_REQUIRED->value)
        );
});

it('returns the correct status code', function (): void {
    getJson(
        uri: action(IndexController::class),
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload', function (): void {
    $categories = Category::factory()->count(1)->create();

    getJson(
        uri: action(IndexController::class),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('data', 1)
                ->has(
                    'data.0',
                    fn (AssertableJson $json) => $json
                        ->where('id', $categories->first()->id)
                        ->where('name', $categories->first()->name)
                        ->where('icon', route('api:v1:images:show', [
                            'path' => $categories->first()->icon
                        ]))
                )
        );
});


it('returns the empty array', function (): void {
    getJson(
        uri: action(IndexController::class),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('data', 0)
                ->where('data', [])
                ->whereType('data', 'array')
        );
});
