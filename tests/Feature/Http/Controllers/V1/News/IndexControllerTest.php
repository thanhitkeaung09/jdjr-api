<?php

declare(strict_types=1);

use App\Http\Controllers\V1\News\IndexController;
use App\Models\News;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;
use function Pest\Laravel\withHeaders;

beforeEach(function (): void {
    withAppKeyHeaders();
});

test('If there are no app keys, it is not possible to get all news', function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    Sanctum::actingAs(User::factory()->create());

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

test('If the app keys are outdated, it is not possible to get all news', function (): void {
    withAppKeyHeaders(true);

    Sanctum::actingAs(User::factory()->create());

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

test('Unauthenicated user cannot get all news', function (): void {
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
    Sanctum::actingAs(User::factory()->create());

    getJson(
        uri: action(IndexController::class),
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload when news is empty in db', function (): void {
    Sanctum::actingAs(User::factory()->create());

    getJson(
        uri: action(IndexController::class),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('data', [])
                ->hasAll('links', 'meta'),
        );
});

it('returns the correct payload', function (): void {
    Sanctum::actingAs(User::factory()->create());

    News::factory()->count(3)->create();

    getJson(
        uri: action(IndexController::class),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('data', 3)
                ->has(
                    'data.0',
                    fn (AssertableJson $json) => $json
                        ->hasAll(['id', 'title', 'shortBody', 'body', 'thumbnail', 'created', 'updated', 'saved', 'savable', 'liked', 'likedCount'])
                        ->whereAllType([
                            'created' => 'array',
                            'updated' => 'array',
                            'saved' => 'boolean',
                            'savable' => 'array|null',
                            'liked' => 'boolean',
                            'likedCount' => 'integer',
                        ])
                        ->whereType('created.string', 'string')
                        ->whereType('created.human', 'string')
                )
                ->hasAll('links', 'meta'),
        );
});
