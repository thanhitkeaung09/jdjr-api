<?php

declare(strict_types=1);

use App\Events\LikeEvent;
use App\Http\Controllers\V1\News\LikeController;
use App\Models\News;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\postJson;
use function Pest\Laravel\withHeaders;

beforeEach(function (): void {
    withAppKeyHeaders();
});

test('If there are no app keys, it is not possible to like a news', function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    Sanctum::actingAs(User::factory()->create());

    $news = News::factory()->create();

    postJson(
        uri: action(LikeController::class, ['news' => $news]),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

test('If the app keys are outdated, it is not possible to like a news', function (): void {
    withAppKeyHeaders(true);

    Sanctum::actingAs(User::factory()->create());
    $news = News::factory()->create();

    postJson(
        uri: action(LikeController::class, ['news' => $news]),
    )
        ->assertStatus(Http::UPGRADE_REQUIRED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.outdated'))
                ->where('description', \trans('message.exceptions.invalid_app_keys'))
                ->where('status', Http::UPGRADE_REQUIRED->value)
        );
});

test('Unauthenicated user cannot like a news', function (): void {
    $news = News::factory()->create();

    postJson(
        uri: action(LikeController::class, ['news' => $news]),
    )
        ->assertStatus(Http::UNAUTHORIZED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthenicated'))
                ->where('description', 'Unauthenticated.')
                ->where('status', Http::UNAUTHORIZED->value)
        );
});

it('returns the correct status code when like a news', function (): void {
    Sanctum::actingAs(User::factory()->create());

    $news = News::factory()->create();

    Event::fake();

    postJson(
        uri: action(LikeController::class, ['news' => $news]),
        data: ['like' => true]
    )
        ->assertStatus(Http::OK->value);

    expect($news->likes()->count())->toBe(1);

    Event::assertDispatched(LikeEvent::class, 1);
});

it('returns the correct status code when unlike a news', function (): void {
    /** @var User */
    Sanctum::actingAs($user = User::factory()->create());

    $news = News::factory()->create();

    $user->likedNews()->attach($news);

    Event::fake();

    postJson(
        uri: action(LikeController::class, ['news' => $news]),
        data: ['like' => false]
    )
        ->assertStatus(Http::OK->value);

    expect($news->likes()->count())->toBe(0);

    Event::assertDispatched(LikeEvent::class, 1);
});

it('returns the correct payload when like a news', function (): void {
    Sanctum::actingAs(User::factory()->create());

    $news = News::factory()->create();

    Event::fake();

    postJson(
        uri: action(LikeController::class, ['news' => $news]),
        data: ['like' => true]
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', \trans('message.like'))
        );

    expect($news->likes()->count())->toBe(1);

    Event::assertDispatched(LikeEvent::class, 1);
    Event::assertDispatched(LikeEvent::class, fn ($e) => $e->news->is($news) && true === $e->like);
});

it('returns the correct payload when unlike a news', function (): void {
    /** @var User */
    Sanctum::actingAs($user = User::factory()->create());

    $news = News::factory()->create();

    $user->likedNews()->attach($news);

    Event::fake();

    postJson(
        uri: action(LikeController::class, ['news' => $news]),
        data: ['like' => false]
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', \trans('message.unlike'))
        );

    expect($news->likes()->count())->toBe(0);

    Event::assertDispatched(LikeEvent::class, 1);
    Event::assertDispatched(LikeEvent::class, fn ($e) => $e->news->is($news) && false === $e->like);
});
