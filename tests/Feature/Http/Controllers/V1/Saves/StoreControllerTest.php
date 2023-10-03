<?php

declare(strict_types=1);

use App\Enums\SavableType;
use App\Http\Controllers\V1\Saves\StoreController;
use App\Models\Folder;
use App\Models\Job;
use App\Models\News;
use App\Models\Savable;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;
use function Pest\Laravel\withHeaders;

beforeEach(fn () => withAppKeyHeaders());

test('If there are no app keys, it is not possible to save', function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    Sanctum::actingAs($user = User::factory()->create());

    postJson(
        uri: action(StoreController::class),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

test('If the app keys are outdated, it is not possible to save', function (): void {
    withAppKeyHeaders(true);

    Sanctum::actingAs($user = User::factory()->create());

    postJson(
        uri: action(StoreController::class),
    )
        ->assertStatus(Http::UPGRADE_REQUIRED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.outdated'))
                ->where('description', \trans('message.exceptions.invalid_app_keys'))
                ->where('status', Http::UPGRADE_REQUIRED->value)
        );
});

it('returns the validation errors response, when the request folder is invalid', function ($folderId): void {
    Sanctum::actingAs($user = User::factory()->create());
    $news = News::factory()->create();

    postJson(
        uri: action(StoreController::class),
        data: [
            'savable_type' => SavableType::NEWS->value,
            'savable_id' => $news->id,
            'folder_id' => $folderId,
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('folder_id'));
})->with('validation_folders');

it("returns the validation errors response, when the other user's folder", function (): void {
    Sanctum::actingAs($user = User::factory()->create());
    $news = News::factory()->create();
    $folder = Folder::factory()->create();

    postJson(
        uri: action(StoreController::class),
        data: [
            'savable_type' => SavableType::NEWS->value,
            'savable_id' => $news->id,
            'folder_id' => $folder->id,
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('folder_id'));
});

it('returns the validation errors response, when the request savable type is invalid', function ($savableType): void {
    Sanctum::actingAs($user = User::factory()->create());
    $news = News::factory()->create();
    $folder = Folder::factory()->for($user)->create();

    postJson(
        uri: action(StoreController::class),
        data: [
            'savable_type' => $savableType,
            'savable_id' => $news->id,
            'folder_id' => $folder->id,
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('savable_type'));
})->with('validation_savable_types');


it('returns the validation errors response, when the request savable id is invalid', function ($savableId): void {
    Sanctum::actingAs($user = User::factory()->create());
    $folder = Folder::factory()->for($user)->create();

    postJson(
        uri: action(StoreController::class),
        data: [
            'savable_type' => SavableType::NEWS->value,
            'savable_id' => $savableId,
            'folder_id' => $folder->id,
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('savable_id'));
})->with('validation_savable_ids');

test('Unauthenticated user cannot save news or job', function (): void {
    $user = User::factory()->create();
    $folder = Folder::factory()->for($user)->create();
    $news = News::factory()->create();

    postJson(
        uri: action(StoreController::class),
        data: [
            'savable_type' => 'news',
            'savable_id' => $news->id,
            'folder_id' => $folder->id,
        ]
    )
        ->assertStatus(Http::UNAUTHORIZED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthenicated'))
                ->where('description', 'Unauthenticated.')
                ->where('status', Http::UNAUTHORIZED->value),
        );
});

it('returns the correct status code when a news is saved', function (): void {
    Sanctum::actingAs($user = User::factory()->create());
    $folder = Folder::factory()->for($user)->create();
    $news = News::factory()->create();

    postJson(
        uri: action(StoreController::class),
        data: [
            'savable_type' => 'news',
            'savable_id' => $news->id,
            'folder_id' => $folder->id,
        ]
    )
        ->assertStatus(Http::OK->value);
});


it('returns the correct status code when a job is saved', function (): void {
    Sanctum::actingAs($user = User::factory()->create());
    $folder = Folder::factory()->for($user)->create();
    $job = Job::factory()->create();

    postJson(
        uri: action(StoreController::class),
        data: [
            'savable_type' => 'job',
            'savable_id' => $job->id,
            'folder_id' => $folder->id,
        ]
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload when a news is saved', function (): void {
    Sanctum::actingAs($user = User::factory()->create());
    $folder = Folder::factory()->for($user)->create();
    $news = News::factory()->create();

    postJson(
        uri: action(StoreController::class),
        data: [
            'savable_type' => SavableType::NEWS->value,
            'savable_id' => $news->id,
            'folder_id' => $folder->id,
        ]
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('savableId', $news->id)
                ->where('savableType', SavableType::NEWS->value)
                ->where('folderId', $folder->id)
                ->where('userId', $user->id)
        );

    assertDatabaseCount('savables', 1);
    assertDatabaseHas('savables', [
        'user_id' => $user->id,
        'savable_type' => SavableType::NEWS->toModelString(),
        'savable_id' => $news->id,
        'folder_id' => $folder->id,
    ]);
});

it('returns the correct payload when a job is saved', function (): void {
    Sanctum::actingAs($user = User::factory()->create());
    $folder = Folder::factory()->for($user)->create();
    $job = Job::factory()->create();

    postJson(
        uri: action(StoreController::class),
        data: [
            'savable_type' => SavableType::JOB->value,
            'savable_id' => $job->id,
            'folder_id' => $folder->id,
        ]
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('savableId', $job->id)
                ->where('savableType', SavableType::JOB->value)
                ->where('folderId', $folder->id)
                ->where('userId', $user->id),
        );

    assertDatabaseCount('savables', 1);
    assertDatabaseHas('savables', [
        'user_id' => $user->id,
        'savable_type' => SavableType::JOB->toModelString(),
        'savable_id' => $job->id,
        'folder_id' => $folder->id,
    ]);
});


it('returns the correct payload when a news is unsaved', function (): void {
    Sanctum::actingAs($user = User::factory()->create());
    $folder = Folder::factory()->for($user)->create();
    $news = News::factory()->create();

    Savable::query()->create([
        'user_id' => $user->id,
        'savable_type' => News::class,
        'savable_id' => $news->id,
        'folder_id' => $folder->id,
    ]);

    postJson(
        uri: action(StoreController::class),
        data: [
            'savable_type' => SavableType::NEWS->value,
            'savable_id' => $news->id,
            'folder_id' => $folder->id,
        ]
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('savableId', null)
                ->where('savableType', SavableType::NEWS->value)
                ->where('folderId', null)
                ->where('userId', null)
        );

    assertDatabaseCount('savables', 0);
});

it('returns the correct payload when a job is unsaved', function (): void {
    Sanctum::actingAs($user = User::factory()->create());
    $folder = Folder::factory()->for($user)->create();
    $job = Job::factory()->create();

    Savable::query()->create([
        'user_id' => $user->id,
        'savable_type' => Job::class,
        'savable_id' => $job->id,
        'folder_id' => $folder->id,
    ]);

    postJson(
        uri: action(StoreController::class),
        data: [
            'savable_type' => SavableType::JOB->value,
            'savable_id' => $job->id,
            'folder_id' => $folder->id,
        ]
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('savableId', null)
                ->where('savableType', SavableType::JOB->value)
                ->where('folderId', null)
                ->where('userId', null)
        );

    assertDatabaseCount('savables', 0);
});
