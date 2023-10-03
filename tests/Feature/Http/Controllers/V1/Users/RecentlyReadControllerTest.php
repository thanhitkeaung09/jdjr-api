<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Users\RecentlyReadController;
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

test("If there are no app keys, it is not possible to retrieve recently read news", function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    Sanctum::actingAs(User::factory()->create());

    getJson(
        uri: action(RecentlyReadController::class),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

test('If the app keys are outdated, it is not possible to retrieve recently read news', function (): void {
    withAppKeyHeaders(true);

    Sanctum::actingAs(User::factory()->create());

    getJson(
        uri: action(RecentlyReadController::class),
    )
        ->assertStatus(Http::UPGRADE_REQUIRED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.outdated'))
                ->where('description', \trans('message.exceptions.invalid_app_keys'))
                ->where('status', Http::UPGRADE_REQUIRED->value)
        );
});

test('Unauthenicated user cannot retrieve recently read news', function (): void {
    getJson(
        uri: action(RecentlyReadController::class),
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
        uri: action(RecentlyReadController::class),
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload when the recently read news is empty', function (): void {
    Sanctum::actingAs(User::factory()->create());

    getJson(
        uri: action(RecentlyReadController::class),
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

    $news2 = News::factory()
        ->hasAttached(Folder::factory(), ['user_id' => $user->id], 'folders')
        ->create();

    $user->readedNews()->attach($news, ['created_at' => now()->subHours(1)]);
    $user->readedNews()->attach($news2, ['created_at' => now()->subHours(2)]);

    getJson(
        uri: action(RecentlyReadController::class),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('data', 2)
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
                        ->where('liked', false),
                )
        );
});
