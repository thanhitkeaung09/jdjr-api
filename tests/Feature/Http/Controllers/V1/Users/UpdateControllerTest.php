<?php

declare(strict_types=1);

use App\Enums\Language;
use App\Http\Controllers\V1\Users\UpdateController;
use App\Models\User;
use App\Services\FileStorage\SpaceStorage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Mockery\MockInterface;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\mock;
use function Pest\Laravel\putJson;
use function Pest\Laravel\withHeaders;

beforeEach(function (): void {
    withAppKeyHeaders();
});

function mockFileUpdate(?string $oldPath, string $newPath): void
{
    mock(SpaceStorage::class, function (MockInterface $mock) use ($oldPath, $newPath): void {
        $mock->shouldReceive('delete')
            ->with($oldPath)
            ->andReturn($oldPath ? true : false);

        $mock->shouldReceive('upload')
            ->andReturn($newPath);
    });
}

test('If there are no app keys, it is not possible to update user', function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    Sanctum::actingAs(User::factory()->create());

    putJson(
        uri: action(UpdateController::class),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

test('If the app keys are outdated, it is not possible to resend otp', function (): void {
    withAppKeyHeaders(true);

    Sanctum::actingAs(User::factory()->create());

    putJson(
        uri: action(UpdateController::class),
    )
        ->assertStatus(Http::UPGRADE_REQUIRED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.outdated'))
                ->where('description', \trans('message.exceptions.invalid_app_keys'))
                ->where('status', Http::UPGRADE_REQUIRED->value)
        );
});

test('Unauthenicated user cannot update their profile', function (): void {
    putJson(
        uri: action(UpdateController::class),
    )
        ->assertStatus(Http::UNAUTHORIZED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthenicated'))
                ->where('description', 'Unauthenticated.')
                ->where('status', Http::UNAUTHORIZED->value)
        );
});

it('returns the validation errors when a user was updated with invalid name', function ($name): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'name' => $name,
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('name'));
})->with('validation_names');

it('returns the validation errors when a user was updated with invalid email', function ($email): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'email' => $email,
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('email'));
})->with('validation_emails');

it('returns the validation errors when a user was updated with invalid password', function ($password): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'password' => $password,
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('password'));
})->with('validation_passwords');

it('returns the validation errors when a user was updated with invalid phone', function ($phone): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'phone' => $phone,
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('phone'));
})->with('validation_phones');

it('returns the validation errors when a user was updated with invalid device token', function ($deviceToken): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'device_token' => $deviceToken,
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('device_token'));
})->with('validation_device_tokens');

it('returns the validation errors when a user was updated with invalid language', function ($language): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'language' => $language,
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('language'));
})->with('validation_languages');

it('returns the validation errors when a user was updated with invalid profile', function ($profile): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'profile' => $profile,
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('profile'));
})->with('validation_profiles');

it('returns the validation errors when a user was updated with invalid position', function ($position): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'current_position' => $position,
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('current_position'));
})->with('validation_positions');

it('returns the validation errors when a user was updated with invalid experience', function ($experience): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'experience_id' => $experience,
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('experience_id'));
})->with('validation_experiences');

it('returns the validation errors when a user was updated with invalid location', function ($location): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'skills' => ['test'],
            'location_id' => $location,
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('location_id'));
})->with('validation_locations');

it('returns the validation errors when a user is updated with a null skill value', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'skills' => null,
        ]
    )
        ->assertStatus(Http::OK->value);
});

it('returns the validation errors when a user is updated a string value for the skill field', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'skills' => 'string',
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('skills'));
});

it('returns the validation errors when a user was updated with invalid skill id', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'skills' => ['test'],
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('skills.0'));
});

it('returns the validation errors when a user is updated with a null interest value', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'interests' => null,
        ]
    )
        ->assertStatus(Http::OK->value);
});

it('returns the validation errors when a user is updated a string value for the interest field', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'interests' => 'string',
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('interests'));
});

it('returns the validation errors when a user was updated with invalid interest id', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'interests' => ['test'],
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('interests.0'));
});

it('returns the validation errors when a user is updated with an interest count less than 3', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'interests' => [1, 2],
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('interests'));
});

it('returns the validation errors when a user is updated with an interest count greater than 5', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'interests' => [1, 2, 3, 4, 5, 6],
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('interests'));
});

it('should update same email for current user (social login)', function (): void {
    User::factory()->email()->create(['email' => 'test@gmail.com']);
    $user = User::factory()->google()->create(['email' => 'test@gmail.com']);

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'email' => $user->email,
        ]
    )
        ->assertStatus(Http::OK->value);
});

it('should update same email for current user (email login)', function (): void {
    User::factory()->google()->create(['email' => 'test@gmail.com']);
    $user = User::factory()->email()->create(['email' => 'test@gmail.com']);

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'email' => $user->email,
        ]
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct status code when a user name was updated', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'name' => 'New User',
        ]
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct status code when a user email was updated', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'email' => 'test@gmail.com',
        ]
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct status code when a user password was updated', function (): void {
    $user = User::factory()->withPassword()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'password' => 'new-password',
        ]
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct status code when a user phone was updated', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'phone' => '09838238282',
        ]
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct status code when a user device token was updated', function (): void {
    $user = User::factory()->create();
    $deviceToken = Str::random();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'device_token' => $deviceToken,
        ]
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct status code when a user profile was updated', function (): void {
    $user = User::factory()->withProfile()->create();

    Sanctum::actingAs($user);

    mockFileUpdate($user->profile, 'profiles/update.png');

    putJson(
        uri: action(UpdateController::class),
        data: [
            'profile' => UploadedFile::fake()->image('update.png'),
        ]
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct status code when a user language was updated', function (): void {
    $user = User::factory()->mm()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'language' => Language::EN->value,
        ]
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload when a user name was updated', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'name' => 'New User',
        ]
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', trans('message.update.success'))
        );

    assertDatabaseCount('users', 1);
    assertDatabaseHas('users', ['id' => $user->id, 'name' => 'New User']);
});

it('returns the correct payload when a user email was updated', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'email' => 'new-user@gmail.com',
        ]
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', trans('message.update.success'))
        );

    assertDatabaseCount('users', 1);
    assertDatabaseHas('users', ['id' => $user->id, 'email' => 'new-user@gmail.com']);
});

it('returns the correct payload when a user password was updated', function (): void {
    $user = User::factory()->withPassword()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'password' => 'new-password',
        ]
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', trans('message.update.success'))
        );

    assertDatabaseCount('users', 1);
    expect(Hash::check('new-password', $user->refresh()->password))->toBeTrue();
});

it('returns the correct payload when a user phone was updated', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'phone' => '098282882828',
        ]
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', trans('message.update.success'))
        );

    assertDatabaseCount('users', 1);
    assertDatabaseHas('users', ['id' => $user->id, 'phone' => '098282882828']);
});

it('returns the correct payload when a user device token was updated', function (): void {
    $user = User::factory()->create();
    $deviceToken = Str::random();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'device_token' => $deviceToken,
        ]
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', trans('message.update.success'))
        );

    assertDatabaseCount('users', 1);
    assertDatabaseHas('users', ['id' => $user->id, 'device_token' => $deviceToken]);
});

it('returns the correct payload when a user language was updated', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'language' => Language::MM->value,
        ]
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', trans('message.update.success'))
        );

    assertDatabaseCount('users', 1);
    assertDatabaseHas('users', ['id' => $user->id, 'language' => Language::MM->value]);
});

it('should update for a user with a profile picture', function (): void {
    $user = User::factory()->withProfile()->create();

    Sanctum::actingAs($user);

    mockProfileUploadNotCall();

    putJson(
        uri: action(UpdateController::class),
        data: [
            'name' => 'New Name',
        ]
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', trans('message.update.success'))
        );

    assertDatabaseCount('users', 1);
    assertDatabaseHas('users', ['profile' => $user->profile, 'name' => 'New Name']);
});

it('should update for a user without a profile picture', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    mockProfileUploadNotCall();

    putJson(
        uri: action(UpdateController::class),
        data: [
            'name' => 'New Name',
        ]
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', trans('message.update.success'))
        );

    assertDatabaseCount('users', 1);
    assertDatabaseHas('users', ['profile' => $user->profile, 'name' => 'New Name']);
});

it('should update for a user if a current_position is null', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'current_position' => null,
        ]
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', trans('message.update.success'))
        );

    assertDatabaseCount('users', 1);
    assertDatabaseHas('users', ['current_position' => null]);
});

it('should update for a user if a experience_id is null', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'experience_id' => null,
        ]
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', trans('message.update.success'))
        );

    assertDatabaseCount('users', 1);
    assertDatabaseHas('users', ['experience_id' => null]);
});

it('should update for a user if a location_id is null', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    putJson(
        uri: action(UpdateController::class),
        data: [
            'location_id' => null,
        ]
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', trans('message.update.success'))
        );

    assertDatabaseCount('users', 1);
    assertDatabaseHas('users', ['location_id' => null]);
});

it('should update for a user if a profile picture is null', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    mockProfileUploadNotCall();

    putJson(
        uri: action(UpdateController::class),
        data: [
            'profile' => null,
        ]
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', trans('message.update.success'))
        );

    assertDatabaseCount('users', 1);
    assertDatabaseHas('users', ['profile' => null]);
});
