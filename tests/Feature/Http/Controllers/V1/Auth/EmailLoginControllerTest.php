<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Auth\EmailLoginController;
use App\Mail\SendOtpCode;
use JustSteveKing\StatusCode\Http;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\postJson;
use function Pest\Laravel\withHeaders;

beforeEach(function (): void {
    withAppKeyHeaders();
});

test('If there are no app keys, it is not possible to log in with an email', function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    postJson(
        uri: action(EmailLoginController::class),
        data: [],
    )->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

test('If the app keys are outdated, it is not possible to log in with an email', function (): void {
    withAppKeyHeaders(true);

    postJson(
        uri: action(EmailLoginController::class),
        data: [],
    )->assertStatus(Http::UPGRADE_REQUIRED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.outdated'))
                ->where('description', \trans('message.exceptions.invalid_app_keys'))
                ->where('status', Http::UPGRADE_REQUIRED->value)
        );
});

it('returns the validation errors when email dose not meet requirements', function ($email): void {
    postJson(
        uri: action(EmailLoginController::class),
        data: [
            'email' => $email,
            'password' => 'password',
        ],
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('email'));
})->with('validation_emails');

it('returns the validation errors when password dose not meet requirements', function ($password): void {
    postJson(
        uri: action(EmailLoginController::class),
        data: [
            'email' => 'test@gmail.com',
            'password' => $password,
        ],
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('password'));
})->with('validation_passwords');

it('returns the validation errors when the email is not registered', function (): void {
    postJson(
        uri: action(EmailLoginController::class),
        data: [
            'email' => 'test@gmail.com',
            'password' => 'password',
        ],
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('email'));
});

test('The login attempt must fail when the password is incorrect', function (): void {
    User::factory()->withVerified()->withPassword('12345678')->create(['email' => 'test@gmail.com']);

    postJson(
        uri: action(EmailLoginController::class),
        data: [
            'email' => 'test@gmail.com',
            'password' => 'password',
        ],
    )
        ->assertStatus(Http::UNAUTHORIZED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', 'Login Failed!')
                ->where('description', 'User credentials did not match!')
                ->where('status', Http::UNAUTHORIZED->value)
        );
});

test('The login attempt must fail when the email is not verified', function (): void {
    Mail::fake();

    $user = User::factory()->withPassword()->create(['email' => 'test@gmail.com']);

    postJson(
        uri: action(EmailLoginController::class),
        data: [
            'email' => 'test@gmail.com',
            'password' => 'password',
        ],
    )
        ->assertStatus(Http::NOT_ACCEPTABLE->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.email_not_verified'))
                ->where('description', 'Your email is not verified yet!')
                ->where('status', Http::NOT_ACCEPTABLE->value)
        );

    Mail::assertQueued(SendOtpCode::class);
    Mail::assertQueued(
        fn (SendOtpCode $mail) =>
        $mail->user->name === $user->name &&
            $mail->user->email === $user->email
    );
});

it('returns the correct status code', function (): void {
    User::factory()->withPassword()->withVerified()->create(['email' => 'test@gmail.com']);

    postJson(
        uri: action(EmailLoginController::class),
        data: [
            'email' => 'test@gmail.com',
            'password' => 'password',
        ],
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload', function (): void {
    User::factory()->withPassword()->withVerified()->create(['email' => 'test@gmail.com']);

    postJson(
        uri: action(EmailLoginController::class),
        data: [
            'email' => 'test@gmail.com',
            'password' => 'password',
        ],
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('token')
                ->whereType('token', 'string')
        );
});
