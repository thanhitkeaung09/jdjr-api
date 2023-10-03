<?php

declare(strict_types=1);

use App\Http\Controllers\V1\News\ShowController;
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

test('If there are no app keys, it is not possible to show news', function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    Sanctum::actingAs(User::factory()->create());

    $news = News::factory()->create();

    getJson(
        uri: action(ShowController::class, ['news' => $news]),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

test('If the app keys are outdated, it is not possible to show news', function (): void {
    withAppKeyHeaders(true);

    Sanctum::actingAs(User::factory()->create());

    $news = News::factory()->create();

    getJson(
        uri: action(ShowController::class, ['news' => $news]),
    )
        ->assertStatus(Http::UPGRADE_REQUIRED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.outdated'))
                ->where('description', \trans('message.exceptions.invalid_app_keys'))
                ->where('status', Http::UPGRADE_REQUIRED->value)
        );
});

test('Unauthenicated user cannot show news', function (): void {
    $news = News::factory()->create();

    getJson(
        uri: action(ShowController::class, ['news' => $news]),
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

    $news = News::factory()->create();

    getJson(
        uri: action(ShowController::class, ['news' => $news]),
    )
        ->assertStatus(Http::OK->value);
});

it('returns the not found exception when not found', function (): void {
    Sanctum::actingAs(User::factory()->create());

    getJson(
        uri: action(ShowController::class, ['news' => 'fdkja-fdajfkad-dfjadjfka']),
    )
        ->assertStatus(Http::NOT_FOUND->value);
});

it('returns the correct payload', function (): void {
    Sanctum::actingAs($user = User::factory()->create());

    $news = News::factory()
        ->hasAttached(Folder::factory(), ['user_id' => $user->id], 'folders')
        ->create();

    getJson(
        uri: action(ShowController::class, ['news' => $news]),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('id', $news->id)
                ->where('title', $news->title)
                ->where('shortBody', $news->short_body)
                ->where('body', $news->body)
                ->where('thumbnail', route('api:v1:images:show', ['path' => $news->thumbnail]))
                ->hasAll(['created', 'updated', 'liked', 'likedCount'])
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
                ->whereAllType([
                    'created' => 'array',
                    'updated' => 'array',
                    'saved' => 'boolean',
                    'liked' => 'boolean',
                    'likedCount' => 'integer',
                ])
                ->whereType('created.string', 'string')
                ->whereType('created.human', 'string')
        );
});
