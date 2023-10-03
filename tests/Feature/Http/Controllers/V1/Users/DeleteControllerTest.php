<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Users\DeleteController;
use App\Models\Category;
use App\Models\Folder;
use App\Models\News;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\deleteJson;

beforeEach(function (): void {
    withAppKeyHeaders();
});

it('should not delete to other user', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs(User::factory()->create());

    deleteJson(
        uri: action(DeleteController::class, ['user' => $user]),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

it('should delete also to user related data', function (): void {
    $user = User::factory()
        ->has(Skill::factory()->count(1))
        ->has(Category::factory()->count(1), 'interests')
        ->has(News::factory()->count(1), 'likedNews')
        ->has(News::factory()->count(1), 'readedNews')
        ->has(Folder::factory()->count(1))
        ->create();

    Folder::factory()->for($user)->create();

    Sanctum::actingAs($user);

    deleteJson(
        uri: action(DeleteController::class, ['user' => $user]),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', \trans('message.delete.success'))
        );

    assertDatabaseCount('users', 1);
    expect($user->refresh()->deleted_at)->not->toBeNull();
    expect($user->readedNews()->count())->toBe(0);
    expect($user->likedNews()->count())->toBe(0);
    expect($user->folders()->count())->toBe(0);
    expect($user->interests()->count())->toBe(0);
    expect($user->skills()->count())->toBe(0);
});

it('returns the correct status code', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    deleteJson(
        uri: action(DeleteController::class, ['user' => $user]),
    )
        ->assertStatus(Http::OK->value);

    assertDatabaseCount('users', 1);
    expect($user->refresh()->deleted_at)->not->toBeNull();
});

it('returns the correct payload', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    deleteJson(
        uri: action(DeleteController::class, ['user' => $user]),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', \trans('message.delete.success'))
        );

    assertDatabaseCount('users', 1);
    expect($user->refresh()->deleted_at)->not->toBeNull();
});
