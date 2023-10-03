<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Jobs\SearchController;
use App\Models\Folder;
use App\Models\Job;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;
use function Pest\Laravel\withHeaders;

beforeEach(function (): void {
    withAppKeyHeaders();
});

test('If there are no app keys, it is not possible to search jobs', function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    Sanctum::actingAs(User::factory()->create());

    getJson(
        uri: action(SearchController::class),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

test('If the app keys are outdated, it is not possible to search jobs', function (): void {
    withAppKeyHeaders(true);

    Sanctum::actingAs(User::factory()->create());

    getJson(
        uri: action(SearchController::class),
    )
        ->assertStatus(Http::UPGRADE_REQUIRED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.outdated'))
                ->where('description', \trans('message.exceptions.invalid_app_keys'))
                ->where('status', Http::UPGRADE_REQUIRED->value)
        );
});

test('Unauthenicated user cannot search jobs', function (): void {
    getJson(
        uri: action(SearchController::class),
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
    Sanctum::actingAs(User::factory()->create());

    getJson(
        uri: action(SearchController::class),
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload when jobs are empty', function (): void {
    Sanctum::actingAs(User::factory()->create());

    getJson(
        uri: action(SearchController::class),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('data', [])
                ->whereType('data', 'array'),
        );
});

it('returns the correct payload', function (): void {
    Sanctum::actingAs($user = User::factory()->create());

    $job = Job::factory()
        ->hasAttached(Folder::factory(), ['user_id' => $user->id], 'folders')
        ->create();

    getJson(
        uri: action(SearchController::class),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('data', 1)
                ->has(
                    'data.0',
                    fn (AssertableJson $json) => $json
                        ->where('id', $job->id)
                        ->where('title', $job->title)
                        ->where('image', route('api:v1:images:show', ['path' => $job->icon]))
                        ->hasAll(['created', 'updated'])
                        ->where('toolsRemark', $job->tools_remark)
                        ->where('saved', true)
                        ->has(
                            'savable',
                            fn (AssertableJson $json) => $json
                                ->hasAll(['savableId', 'folderId', 'savableType', 'userId'])
                                ->whereAllType([
                                    "savableId" => "string",
                                    "folderId" => "string",
                                    "savableType" => "string",
                                    "userId" => "string",
                                ])
                        )
                        ->whereAllType([
                            'created' => 'array',
                            'updated' => 'array',
                        ]),
                ),
        );
});

it('can search by title correctly', function (): void {
    Sanctum::actingAs($user = User::factory()->create());

    $job = Job::factory()
        ->hasAttached(Folder::factory(), ['user_id' => $user->id], 'folders')
        ->create(['title' => 'Test Job']);
    Job::factory()->create(['title' => 'Other Job']);

    getJson(
        uri: action(SearchController::class, ['search' => 'test']),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('data', 1)
                ->has(
                    'data.0',
                    fn (AssertableJson $json) => $json
                        ->where('id', $job->id)
                        ->where('title', $job->title)
                        ->where('image', route('api:v1:images:show', ['path' => $job->icon]))
                        ->hasAll(['created', 'updated'])
                        ->where('toolsRemark', $job->tools_remark)
                        ->where('saved', true)
                        ->has(
                            'savable',
                            fn (AssertableJson $json) => $json
                                ->hasAll(['savableId', 'folderId', 'savableType', 'userId'])
                                ->whereAllType([
                                    "savableId" => "string",
                                    "folderId" => "string",
                                    "savableType" => "string",
                                    "userId" => "string",
                                ])
                        )
                        ->whereAllType([
                            'created' => 'array',
                            'updated' => 'array',
                        ]),
                ),
        );
});

it('can search by subcategory id correctly', function (): void {
    Sanctum::actingAs($user = User::factory()->create());

    $job = Job::factory()
        ->hasAttached(Folder::factory(), ['user_id' => $user->id], 'folders')
        ->create();
    Job::factory()->create();

    getJson(
        uri: action(SearchController::class, ['subcategory_id' => $job->subcategory_id]),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('data', 1)
                ->has(
                    'data.0',
                    fn (AssertableJson $json) => $json
                        ->where('id', $job->id)
                        ->where('title', $job->title)
                        ->where('image', route('api:v1:images:show', ['path' => $job->icon]))
                        ->hasAll(['created', 'updated'])
                        ->where('toolsRemark', $job->tools_remark)
                        ->where('saved', true)
                        ->has(
                            'savable',
                            fn (AssertableJson $json) => $json
                                ->hasAll(['savableId', 'folderId', 'savableType', 'userId'])
                                ->whereAllType([
                                    "savableId" => "string",
                                    "folderId" => "string",
                                    "savableType" => "string",
                                    "userId" => "string",
                                ])
                        )
                        ->whereAllType([
                            'created' => 'array',
                            'updated' => 'array',
                        ]),
                ),
        );
});

it('can search by category id correctly', function (): void {
    Sanctum::actingAs(User::factory()->create());

    $job = Job::factory()->create();
    Job::factory()->create();

    getJson(
        uri: action(SearchController::class, ['category_id' => $job->subcategory->category_id]),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('data', 1)
                ->has(
                    'data.0',
                    fn (AssertableJson $json) => $json
                        ->where('id', $job->id)
                        ->where('title', $job->title)
                        ->where('image', route('api:v1:images:show', ['path' => $job->icon]))
                        ->hasAll(['created', 'updated'])
                        ->where('toolsRemark', $job->tools_remark)
                        ->where('saved', false)
                        ->where('savable', null)
                        ->whereAllType([
                            'created' => 'array',
                            'updated' => 'array',
                        ]),
                ),
        );
});

it('can search correctly', function (): void {
    Sanctum::actingAs(User::factory()->create());

    $job = Job::factory()->create();
    Job::factory()->create(['title' => 'Test Job']);

    getJson(
        uri: action(SearchController::class, [
            'category_id' => $job->subcategory->category_id,
            'search' => 'test'
        ]),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('data', 0),
        );
});
