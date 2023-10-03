<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AppVersions\IndexController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\LogoutController;
use App\Models\Admin;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

test('An unauthenticated user cannot log out', function (): void {
    postJson(
        uri: action(LogoutController::class),
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
    /** @var Admin */
    $user = Admin::factory()->create(['email' => 'test@gmail.com']);

    Sanctum::actingAs($user, guard: 'admin');

    postJson(
        uri: action(LogoutController::class),
    )
        ->assertStatus(Http::OK->value);

    expect($user->tokens()->count())->toEqual(0);
});

it('returns the correct payload', function (): void {
    /** @var Admin */
    $user = Admin::factory()->create(['email' => 'test@gmail.com']);

    Sanctum::actingAs($user, guard: 'admin');

    postJson(
        uri: action(LogoutController::class),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', \trans('message.logout.success'))
        );

    expect($user->tokens()->count())->toEqual(0);
});

it('should not delete tokens that belong to the same account on other devices', function (): void {
    /** @var Admin */
    $user = Admin::factory()->create(['email' => 'test@gmail.com']);

    $other = postJson(
        uri: action(LoginController::class),
        data: ['email' => $user->email, 'password' => 'password'],
    )->assertOk();

    $response = postJson(
        uri: action(LoginController::class),
        data: ['email' => $user->email, 'password' => 'password'],
    )->assertOk();

    postJson(
        uri: action(LogoutController::class),
        headers: ['Authorization' => 'Bearer ' . $response->json('token')]
    )
        ->assertStatus(Http::OK->value);

    expect($user->tokens()->count())->toEqual(1);

    getJson(
        uri: action(IndexController::class),
        headers: ['Authorization' => 'Bearer ' . $other->json('token')]
    )->assertOk();
});
