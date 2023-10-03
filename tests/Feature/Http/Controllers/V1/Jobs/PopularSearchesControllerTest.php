<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Jobs\PopularSearchesController;
use App\Models\Job;
use App\Models\PopularSearch;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;
use function Pest\Laravel\withHeaders;

beforeEach(function (): void {
    withAppKeyHeaders();
});

test('If there are no app keys, it is not possible to get popular searches', function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    Sanctum::actingAs(User::factory()->create());

    getJson(
        uri: action(PopularSearchesController::class),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

test('If the app keys are outdated, it is not possible to get popular searches', function (): void {
    withAppKeyHeaders(true);

    Sanctum::actingAs(User::factory()->create());

    getJson(
        uri: action(PopularSearchesController::class),
    )
        ->assertStatus(Http::UPGRADE_REQUIRED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.outdated'))
                ->where('description', \trans('message.exceptions.invalid_app_keys'))
                ->where('status', Http::UPGRADE_REQUIRED->value)
        );
});

test('Unauthenicated user cannot get popular searches', function (): void {
    $job = Job::factory()->create();

    getJson(
        uri: action(PopularSearchesController::class),
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
        uri: action(PopularSearchesController::class),
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload when popular searches are empty', function (): void {
    Sanctum::actingAs(User::factory()->create());

    getJson(
        uri: action(PopularSearchesController::class),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('data', [])
                ->whereType('data', 'array')
        );
});

it('returns the correct payload', function (): void {
    Sanctum::actingAs(User::factory()->create());

    $job = Job::factory()->create();

    $search = PopularSearch::query()->create([
        'job_id' => $job->id,
        'job_title' => $job->title,
    ]);

    getJson(
        uri: action(PopularSearchesController::class),
    )
        ->assertStatus(Http::OK->value)
        ->dump()
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('data', 1)
                ->has(
                    'data.0',
                    fn (AssertableJson $json) => $json
                        ->where('jobId', $search->job_id)
                        ->where('jobTitle', $search->job_title)
                ),
        );
});
