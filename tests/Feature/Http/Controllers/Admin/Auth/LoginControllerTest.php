<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Models\Admin;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\postJson;

it('returns the validation errors when a request payload is invalid', function ($email, $password): void {
    postJson(
        uri: action(LoginController::class),
        data: [
            'email' => $email,
            'password' => $password,
        ],
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value);
})->with('validation_emails')->with('validation_passwords');

it('cannot login with wrong crendentials', function (): void {
    $admin = Admin::factory()->create();

    postJson(
        uri: action(LoginController::class),
        data: [
            'email' => $admin->email,
            'password' => 'wrongpassword',
        ],
    )
        ->assertStatus(Http::UNAUTHORIZED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthenicated'))
                ->where('description', \trans('message.exceptions.wrong_password'))
                ->where('status', Http::UNAUTHORIZED->value)
        );
});

it('returns the correct status code', function (): void {
    $admin = Admin::factory()->create();

    postJson(
        uri: action(LoginController::class),
        data: [
            'email' => $admin->email,
            'password' => 'password',
        ],
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload', function (): void {
    $admin = Admin::factory()->create();

    /** @var Role */
    $role = Role::query()->create(['name' => 'Test']);
    $permission = Permission::query()->create(['name' => 'create-test']);

    $admin->roles()->attach($role->id);
    $role->permissions()->attach($permission->id);

    postJson(
        uri: action(LoginController::class),
        data: [
            'email' => $admin->email,
            'password' => 'password',
        ],
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('token')
                ->has('roles', 1)
                ->has(
                    'roles.0',
                    fn (AssertableJson $json) => $json
                        ->hasAll(['id', 'name'])
                        ->has('permissions', 1)
                )
        );
});
