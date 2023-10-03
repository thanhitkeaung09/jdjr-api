<?php

declare(strict_types=1);

use App\Enums\Language;
use App\Enums\LoginType;
use App\Http\Controllers\V1\Auth\SocialLoginController;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;
use function Pest\Laravel\withHeaders;

function getSocialLoginData(string $type, array $attributes = []): array
{
    return LoginType::GOOGLE->match($type) ?
        User::factory()->google()->make($attributes)->toArray() :
        User::factory()->facebook()->make($attributes)->toArray();
}

beforeEach(function (): void {
    withAppKeyHeaders();
});

test('If there are no app keys, it is not possible to social login', function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    postJson(
        uri: action(SocialLoginController::class, ['type' => 'google']),
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
        uri: action(SocialLoginController::class, ['type' => 'google']),
    )
        ->assertStatus(Http::UPGRADE_REQUIRED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.outdated'))
                ->where('description', \trans('message.exceptions.invalid_app_keys'))
                ->where('status', Http::UPGRADE_REQUIRED->value)
        );
});

it('returns the validation errors when name dose not meet requirements', function ($name, $type): void {
    postJson(
        uri: action(SocialLoginController::class, ['type' => $type]),
        data: getSocialLoginData($type, ['name' => $name]),
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('name'));
})->with('validation_names')->with('login_types');

it('returns the validation errors when email dose not meet requirements', function ($email, $type): void {
    postJson(
        uri: action(SocialLoginController::class, ['type' => $type]),
        data: getSocialLoginData($type, ['email' => $email, 'phone' => null]),
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('email'));
})->with('validation_emails')->with('login_types');

it('returns the correct status when the requests do not have email but phone', function ($type): void {
    postJson(
        uri: action(SocialLoginController::class, ['type' => $type]),
        data: getSocialLoginData($type, ['email' => null]),
    )
        ->assertStatus(Http::OK->value);
})->with('login_types');

it('returns the validation errors when phone dose not meet requirements', function ($phone, $type): void {
    postJson(
        uri: action(SocialLoginController::class, ['type' => $type]),
        data: getSocialLoginData($type, ['phone' => $phone, 'email' => null]),
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('phone'));
})->with('validation_phones')->with('login_types');

it('returns the correct status when the requests do not have phone but email', function ($type): void {
    postJson(
        uri: action(SocialLoginController::class, ['type' => $type]),
        data: getSocialLoginData($type, ['phone' => null]),
    )
        ->assertStatus(Http::OK->value);
})->with('login_types');

it('returns the validation errors when profile dose not meet requirements', function ($profile, $type): void {
    mockProfileUploadNotCall();

    postJson(
        uri: action(SocialLoginController::class, ['type' => $type]),
        data: getSocialLoginData($type, ['profile' => $profile]),
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('profile'));
})->with('validation_profiles')->with('login_types');

it('returns the validation errors when login id dose not meet requirements', function ($loginId, $type): void {
    postJson(
        uri: action(SocialLoginController::class, ['type' => $type]),
        data: getSocialLoginData($type, ['login_id' => $loginId]),
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('login_id'));
})->with('validation_login_ids')->with('login_types');

it('returns the correct status code when social login with profile', function ($type): void {
    mockProfileUpload('test.png');

    postJson(
        uri: action(SocialLoginController::class, ['type' => $type]),
        data: getSocialLoginData($type, [
            'profile' => fake()->imageUrl(width: 5, height: 5),
        ]),
    )
        ->assertStatus(Http::OK->value);

    assertDatabaseCount('users', 1);
    assertDatabaseHas('users', ['profile' => 'profiles/test.png']);
})->with('login_types');

it('returns the correct status code when social login without profile', function ($type): void {
    mockProfileUploadNotCall();

    postJson(
        uri: action(SocialLoginController::class, ['type' => $type]),
        data: getSocialLoginData($type),
    )
        ->assertStatus(Http::OK->value);

    assertDatabaseCount('users', 1);
    assertDatabaseHas('users', ['profile' => null]);
})->with('login_types');

it('returns the correct payload when social login with profile', function ($type): void {
    mockProfileUpload('test.png');

    postJson(
        uri: action(SocialLoginController::class, ['type' => $type]),
        data: getSocialLoginData($type, [
            'profile' => fake()->imageUrl(width: 5, height: 5)
        ]),
    )
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('token')
                ->whereType('token', 'string')
        );

    assertDatabaseCount('users', 1);
    assertDatabaseHas('users', ['login_type' => $type]);
    assertDatabaseHas('users', ['language' => Language::EN->value]);
    assertDatabaseHas('users', ['profile' => 'profiles/test.png']);
})->with('login_types');

it('returns the correct payload when social login without profile', function ($type): void {
    mockProfileUploadNotCall();

    postJson(
        uri: action(SocialLoginController::class, ['type' => $type]),
        data: getSocialLoginData($type),
    )
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('token')
                ->whereType('token', 'string')
        );

    assertDatabaseCount('users', 1);
    assertDatabaseHas('users', ['login_type' => $type]);
    assertDatabaseHas('users', ['language' => Language::EN->value]);
    assertDatabaseHas('users', ['profile' => null]);
})->with('login_types');

it('should not update the existing account', function (string $type): void {
    $loginTypes = [
        LoginType::GOOGLE->value => fn () => User::factory()->google()->create(),
        LoginType::FACEBOOK->value => fn () => User::factory()->facebook()->create(),
        LoginType::APPLE->value => fn () => User::factory()->apple()->create(),
    ];
    $user = $loginTypes[$type]();

    postJson(
        uri: action(SocialLoginController::class, ['type' => $type]),
        data: [
            ...$user->toArray(),
            'name' => 'New Name',
            'email' => 'newname@gmail.com',
            'phone' => '09822828282828',
        ],
    )
        ->assertStatus(Http::OK->value);

    assertDatabaseCount('users', 1);
    assertDatabaseHas('users', [
        'name' => $user->name,
        'email' => $user->email,
        'phone' => $user->phone,
    ]);
})->with('login_types');
