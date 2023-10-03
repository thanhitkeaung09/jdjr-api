<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Folders\ShowController;
use App\Models\Folder;
use App\Models\Job;
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

test('If there are no app keys, it is not possible to get folders', function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    Sanctum::actingAs(User::factory()->create());

    $folder = Folder::factory()->create();

    getJson(
        uri: action(ShowController::class, ['folder' => $folder]),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

test('If the app keys are outdated, it is not possible to get folders', function (): void {
    withAppKeyHeaders(true);

    Sanctum::actingAs(User::factory()->create());

    $folder = Folder::factory()->create();

    getJson(
        uri: action(ShowController::class, ['folder' => $folder]),
    )
        ->assertStatus(Http::UPGRADE_REQUIRED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.outdated'))
                ->where('description', \trans('message.exceptions.invalid_app_keys'))
                ->where('status', Http::UPGRADE_REQUIRED->value)
        );
});

test('Unauthenicated user cannot get folders', function (): void {
    $folder = Folder::factory()->create();

    getJson(
        uri: action(ShowController::class, ['folder' => $folder]),
    )
        ->assertStatus(Http::UNAUTHORIZED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthenicated'))
                ->where('description', 'Unauthenticated.')
                ->where('status', Http::UNAUTHORIZED->value)
        );
});

it('returns the not found exception when not found', function (): void {
    Sanctum::actingAs($user = User::factory()->create());

    getJson(
        uri: action(ShowController::class, ['folder' => 'fdkja-fdajfkad-dfjadjfka']),
    )
        ->assertStatus(Http::NOT_FOUND->value);
});

it("cannot show to other user's folder", function (): void {
    Sanctum::actingAs($user = User::factory()->create());

    Folder::factory()->for($user)->create();
    $folder = Folder::factory()->create();

    getJson(
        uri: action(ShowController::class, ['folder' => $folder]),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

it('returns the correct status code', function (): void {
    Sanctum::actingAs($user = User::factory()->create());

    $folder = Folder::factory()->for($user)->create();

    getJson(
        uri: action(ShowController::class, ['folder' => $folder]),
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload', function (): void {
    Sanctum::actingAs($user = User::factory()->create());

    $folder = Folder::factory()
        ->for($user)
        ->hasAttached(News::factory(), ['user_id' => $user->id])
        ->hasAttached(Job::factory(), ['user_id' => $user->id])
        ->create();

    getJson(
        uri: action(ShowController::class, ['folder' => $folder]),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->hasAll(['id', 'name'])
                ->whereAllType([
                    'id' => 'string',
                    'name' => 'string',
                ])
                ->has('news', 1)
                ->has('jobs', 1)
        );
});
