<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Str;
use JustSteveKing\StatusCode\Http;
use Illuminate\Testing\Fluent\AssertableJson;

use App\Http\Controllers\V1\Auth\AppleLoginController;

use function Pest\Laravel\postJson;
use function Pest\Laravel\withHeaders;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseCount;

use function Pest\Laravel\assertSoftDeleted;

beforeEach(function (): void {
    withAppKeyHeaders();
});

test('If there are no app keys, it is not possible to social login', function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    postJson(
        uri: action(AppleLoginController::class),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

test('If the app keys are outdated, it is not possible to social login', function (): void {
    withAppKeyHeaders(true);

    postJson(
        uri: action(AppleLoginController::class),
    )
        ->assertStatus(Http::UPGRADE_REQUIRED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.outdated'))
                ->where('description', \trans('message.exceptions.invalid_app_keys'))
                ->where('status', Http::UPGRADE_REQUIRED->value)
        );
});

it('should not apple login if input data has not name field', function (): void {
    postJson(
        uri: action(AppleLoginController::class),
        data: [
            'name' => null,
            'email' => fake()->unique()->safeEmail(),
            'login_id' => Str::random(),
        ],
    )
        ->assertStatus(Http::NOT_ACCEPTABLE->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', 'Apple Login Failed!')
                ->where('description', 'User name or email is required!')
                ->where('status', Http::NOT_ACCEPTABLE->value)
        );
});

it('should not apple login if input data has not email field', function (): void {
    postJson(
        uri: action(AppleLoginController::class),
        data: [
            'name' => fake()->userName(),
            'email' => null,
            'login_id' => Str::random(),
        ],
    )
        ->assertStatus(Http::NOT_ACCEPTABLE->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', 'Apple Login Failed!')
                ->where('description', 'User name or email is required!')
                ->where('status', Http::NOT_ACCEPTABLE->value)
        );
});

it('returns the validation errors if input data has not login_id field', function (): void {
    postJson(
        uri: action(AppleLoginController::class),
        data: [
            'name' => fake()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'login_id' => null,
        ],
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('login_id'));
});

it('should login with apple', function (): void {
    postJson(
        uri: action(AppleLoginController::class),
        data: $data = [
            'name' => fake()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'login_id' => Str::random(),
        ],
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('token')
                ->whereType('token', 'string')
        );

    assertDatabaseCount('users', 1);
    assertDatabaseHas('users', $data);
});

it('should login again with apple login id only', function (): void {
    $user = User::factory()->apple()->create();

    postJson(
        uri: action(AppleLoginController::class),
        data: [
            'name' => null,
            'email' => null,
            'login_id' => $user->login_id,
        ],
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('token')
                ->whereType('token', 'string')
        );

    assertDatabaseCount('users', 1);
    assertDatabaseHas('users', [
        'name' => $user->name,
        'email' => $user->email,
        'login_id' => $user->login_id,
    ]);
});

it('should login again with deleted user', function (): void {
    $user = User::factory()->apple()->create();
    $loginId = $user->login_id;

    $user->update(['deleted_at' => now(), 'email' => $user->email . '_' . now()]);
    assertSoftDeleted('users', ['login_id' => $loginId]);

    postJson(
        uri: action(AppleLoginController::class),
        data: [
            'name' => null,
            'email' => null,
            'login_id' => $loginId,
        ],
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('token')
                ->whereType('token', 'string')
        );

    assertDatabaseCount('users', 2);
    assertDatabaseHas('users', [
        'name' => $user->name,
        'email' => $user->email,
        'login_id' => $user->login_id,
    ]);
});
