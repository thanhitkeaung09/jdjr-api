<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Users\GetLikedController;
use App\Models\Folder;
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

test("If there are no app keys, it is not possible to retrieve news liked by the user", function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    Sanctum::actingAs(User::factory()->create());

    getJson(
        uri: action(GetLikedController::class),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

test('If the app keys are outdated, it is not possible to retrieve news liked by the user', function (): void {
    withAppKeyHeaders(true);

    Sanctum::actingAs(User::factory()->create());

    getJson(
        uri: action(GetLikedController::class),
    )
        ->assertStatus(Http::UPGRADE_REQUIRED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.outdated'))
                ->where('description', \trans('message.exceptions.invalid_app_keys'))
                ->where('status', Http::UPGRADE_REQUIRED->value)
        );
});

test('Unauthenicated user cannot retrieve news liked by the user', function (): void {
    getJson(
        uri: action(GetLikedController::class),
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
        uri: action(GetLikedController::class),
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload when user likes news is empty', function (): void {
    Sanctum::actingAs(User::factory()->create());

    getJson(
        uri: action(GetLikedController::class),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('data', [])
                ->whereType('data', 'array'),
        );
});

it('returns the correct payload', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $news = News::factory()
        ->hasAttached(Folder::factory(), ['user_id' => $user->id], 'folders')
        ->create();

    $user->likedNews()->attach($news);

    getJson(
        uri: action(GetLikedController::class),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('data', 1)
                ->has(
                    'data.0',
                    fn (AssertableJson $json) => $json
                        ->where('id', $news->id)
                        ->where('title', $news->title)
                        ->where('shortBody', $news->short_body)
                        ->where('body', $news->body)
                        ->where('thumbnail', route('api:v1:images:show', ['path' => $news->thumbnail]))
                        ->hasAll(['created', 'updated'])
                        ->whereAllType([
                            'created' => 'array',
                            'updated' => 'array',
                        ])
                        ->where('saved', true)
                        ->has(
                            'savable',
                            fn (AssertableJson $json) => $json
                                ->hasAll(['savableId', 'folderId', 'savableType', 'userId'])
                                ->whereAllType([
                                    "savableId" => "string",
                                    "folderId" => "string",
                                    "savableType" => "string",
                                    "userId" => "string",
                                ])
                        )
                        ->where('liked', true),
                )
        );
});
