<?php

declare(strict_types=1);

use App\Enums\Language;
use App\Enums\LoginType;
use App\Http\Controllers\V1\Users\ShowController;
use App\Models\Category;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;
use function Pest\Laravel\withHeaders;

beforeEach(function (): void {
    withAppKeyHeaders();
});

test('If there are no app keys, it is not possible to see user profile', function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    Sanctum::actingAs(User::factory()->create());

    getJson(
        uri: action(ShowController::class),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

test('If the app keys are outdated, it is not possible to see user profile', function (): void {
    withAppKeyHeaders(true);

    Sanctum::actingAs(User::factory()->create());

    getJson(
        uri: action(ShowController::class),
    )
        ->assertStatus(Http::UPGRADE_REQUIRED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.outdated'))
                ->where('description', \trans('message.exceptions.invalid_app_keys'))
                ->where('status', Http::UPGRADE_REQUIRED->value)
        );
});

test('Unauthenticated user cannot see their profile', function (): void {
    getJson(
        uri: action(ShowController::class),
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
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    getJson(
        uri: action(ShowController::class),
    )->assertStatus(Http::OK->value);
});

it('returns the correct payload with default profile', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    getJson(
        uri: action(ShowController::class),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('id', $user->id)
                ->where('name', $user->name)
                ->where('email', $user->email)
                ->where('phone', $user->phone)
                ->where('language', Language::EN->value)
                ->where('loginType', LoginType::GMAIL->value)
                ->where('profile', null)
                ->where('currentPosition', null)
                ->where('deviceToken', null)
                ->where('experience', null)
                ->has(
                    'location',
                    fn (AssertableJson $json) => $json
                        ->where('id', null)
                        ->where('name', null)
                )
                ->where('interests', [])
                ->where('skills', [])
                ->where('folders', [])
                ->has('usersCount')
                ->whereType('usersCount', 'integer')
                ->missingAll(['emailVerifiedAt', 'loginId', 'password'])
        );
});

it('returns the correct payload when a user fills in their informations', function (): void {
    $user = User::factory()
        ->withDeviceToken()
        ->withExperience('5yrs ++', 'senior')
        ->withPosition('Full Stack Web Developer')
        ->withLocation('Yangon')
        ->withProfile()
        ->has(Skill::factory()->count(3))
        ->has(Category::factory()->count(2), 'interests')
        ->create();

    Sanctum::actingAs($user);

    getJson(
        uri: action(ShowController::class),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('id', $user->id)
                ->where('name', $user->name)
                ->where('email', $user->email)
                ->where('phone', $user->phone)
                ->where('language', $user->language->value)
                ->where('loginType', LoginType::GMAIL->value)
                ->where('profile', route('api:v1:images:show', ['path' => $user->profile]))
                ->has(
                    'currentPosition',
                    fn (AssertableJson $json) => $json
                        ->has('id')
                        ->where('title', 'Full Stack Web Developer')
                )
                ->where('deviceToken', $user->device_token)
                ->whereType('experience', 'array')
                ->has(
                    'experience',
                    fn (AssertableJson $json) => $json
                        ->has('id')
                        ->where('duration', '5yrs ++')
                        ->has(
                            'level',
                            fn (AssertableJson $json) => $json->has('id')
                                ->where('name', 'senior')
                        )
                )
                ->whereType('location', 'array')
                ->has(
                    'location',
                    fn (AssertableJson $json) => $json->has('id')
                        ->where('name', 'Yangon')
                )
                ->has('skills', 3)
                ->has('skills.0', fn (AssertableJson $json) => $json->hasAll(['id', 'name', 'icon']))
                ->has('interests', 2)
                ->has('interests.0', fn (AssertableJson $json) => $json->hasAll(['id', 'name', 'icon']))
                ->has('folders', 0)
                ->has('usersCount')
                ->whereType('usersCount', 'integer')
                ->missingAll(['emailVerifiedAt', 'loginId', 'password'])
        );
});
