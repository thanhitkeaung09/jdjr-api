<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Categories\ShowController;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\getJson;
use function Pest\Laravel\withHeaders;

beforeEach(function (): void {
    withAppKeyHeaders();
});

test('If there are no app keys, it is not possible to get subcategories', function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    $category = Category::factory()->create();

    getJson(
        uri: action(ShowController::class, ['category' => $category]),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

test('If the app keys are outdated, it is not possible to get subcategories', function (): void {
    withAppKeyHeaders(true);

    $category = Category::factory()->create();

    getJson(
        uri: action(ShowController::class, ['category' => $category]),
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
    $category = Category::factory()->create();

    getJson(
        uri: action(ShowController::class, ['category' => $category]),
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload', function (): void {
    $category = Category::factory()->has(Subcategory::factory()->count(3))->create();

    getJson(
        uri: action(ShowController::class, ['category' => $category]),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('data', 3)
                ->has(
                    'data.0',
                    fn (AssertableJson $json) => $json
                        ->hasAll(['id', 'name'])
                        ->whereAllType([
                            'id' => 'string',
                            'name' => 'string',
                        ]),
                ),
        );
});

it('returns the empty array', function (): void {
    $category = Category::factory()->create();

    getJson(
        uri: action(ShowController::class, ['category' => $category]),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('data', 0)
                ->where('data', [])
                ->whereType('data', 'array')
        );
});
