<?php

declare(strict_types=1);

use App\Http\Controllers\V1\News\ReadController;
use App\Models\News;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;
use function Pest\Laravel\withHeaders;

beforeEach(function (): void {
    withAppKeyHeaders();
});

test("If there are no app keys, it is not possible to read news", function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    Sanctum::actingAs(User::factory()->create());
    $news = News::factory()->create();

    postJson(
        uri: action(ReadController::class, ['news' => $news]),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

test('If the app keys are outdated, it is not possible to read news', function (): void {
    withAppKeyHeaders(true);

    Sanctum::actingAs(User::factory()->create());
    $news = News::factory()->create();

    postJson(
        uri: action(ReadController::class, ['news' => $news]),
    )
        ->assertStatus(Http::UPGRADE_REQUIRED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.outdated'))
                ->where('description', \trans('message.exceptions.invalid_app_keys'))
                ->where('status', Http::UPGRADE_REQUIRED->value)
        );
});

test('Unauthenicated user cannot read news', function (): void {
    $news = News::factory()->create();

    postJson(
        uri: action(ReadController::class, ['news' => $news]),
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

    postJson(
        uri: action(ReadController::class, ['news' => $news]),
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload when already exists', function (): void {
    Sanctum::actingAs($user = User::factory()->create());
    $user->readedNews()->attach(News::factory()->create()->id);
    $news = News::factory()->create();

    postJson(
        uri: action(ReadController::class, ['news' => $news]),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', \trans('message.read.success'))
        );

    $read = $user->readedNews()->wherePivot('news_id', $news->id)->first();

    assertDatabaseCount('reads', 2);
    assertDatabaseHas('reads', [
        'user_id' => $user->id,
        'news_id' => $news->id,
        'created_at' => $read->created_at,
        'updated_at' => $read->updated_at,
    ]);
});

it('returns the correct payload when already non-existing', function (): void {
    Sanctum::actingAs($user = User::factory()->create());
    $news = News::factory()->create();
    $user->readedNews()->attach($news->id);

    postJson(
        uri: action(ReadController::class, ['news' => $news]),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', \trans('message.read.success'))
        );

    assertDatabaseCount('reads', 1);
    assertDatabaseHas('reads', [
        'user_id' => $user->id,
        'news_id' => $news->id,
    ]);
});
