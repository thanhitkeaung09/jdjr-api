<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Notifications\ReadController;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\postJson;
use function Pest\Laravel\withHeaders;

beforeEach(function (): void {
    withAppKeyHeaders();
});

test('If there are no app keys, it is not possible to read notification', function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    Sanctum::actingAs(User::factory()->create());

    $notification = Notification::factory()->create();

    postJson(
        uri: action(ReadController::class, ['notification' => $notification]),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

test('If the app keys are outdated, it is not possible to read notification', function (): void {
    withAppKeyHeaders(true);

    Sanctum::actingAs(User::factory()->create());
    $notification = Notification::factory()->create();

    postJson(
        uri: action(ReadController::class, ['notification' => $notification]),
    )
        ->assertStatus(Http::UPGRADE_REQUIRED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.outdated'))
                ->where('description', \trans('message.exceptions.invalid_app_keys'))
                ->where('status', Http::UPGRADE_REQUIRED->value)
        );
});

test('Unauthenicated user cannot read notification', function (): void {
    $notification = Notification::factory()->create();

    postJson(
        uri: action(ReadController::class, ['notification' => $notification]),
    )
        ->assertStatus(Http::UNAUTHORIZED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthenicated'))
                ->where('description', 'Unauthenticated.')
                ->where('status', Http::UNAUTHORIZED->value)
        );
});

it("cannot mark other users' notifications as read.", function (): void {
    Sanctum::actingAs(User::factory()->create());

    $notification = Notification::factory()->create();

    postJson(
        uri: action(ReadController::class, ['notification' => $notification]),
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
    $notification = Notification::factory()->create();

    Sanctum::actingAs(User::query()->first());

    postJson(
        uri: action(ReadController::class, ['notification' => $notification]),
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload', function (): void {
    $notification = Notification::factory()->create();

    Sanctum::actingAs(User::query()->first());

    postJson(
        uri: action(ReadController::class, ['notification' => $notification]),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', \trans('message.read.success'))
        );
});
