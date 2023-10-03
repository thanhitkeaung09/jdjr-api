<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Categories\JobTitlesController;
use App\Models\Job;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\postJson;
use function Pest\Laravel\withHeaders;

beforeEach(function (): void {
    withAppKeyHeaders();
});

test('If there are no app keys, it is not possible to get job titles', function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    postJson(
        uri: action(JobTitlesController::class),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

test('If the app keys are outdated, it is not possible to get categories', function (): void {
    withAppKeyHeaders(true);

    postJson(
        uri: action(JobTitlesController::class),
    )
        ->assertStatus(Http::UPGRADE_REQUIRED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.outdated'))
                ->where('description', \trans('message.exceptions.invalid_app_keys'))
                ->where('status', Http::UPGRADE_REQUIRED->value)
        );
});

it('returns the validation errors response, when the request input is invalid', function ($categories): void {
    postJson(
        uri: action(JobTitlesController::class),
        data: [
            'categories' => $categories,
        ]
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.validation'))
                ->has('errors')
                ->whereType('errors', 'array')
                ->where('status', Http::UNPROCESSABLE_ENTITY->value)
        );
})->with('validation_categories');

it('returns the correct status', function (): void {
    $jobs = Job::factory()->count(3)->create()->load('subcategory');

    postJson(
        uri: action(JobTitlesController::class),
        data: [
            'categories' => $jobs->pluck('subcategory.category_id')->toArray(),
        ]
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload with multiple categories', function (): void {
    $jobs = Job::factory()->count(3)->create()->sortBy('title')->load('subcategory');

    $firstJob = $jobs->first();
    $lastJob = $jobs->last();

    postJson(
        uri: action(JobTitlesController::class),
        data: [
            'categories' => $jobs->pluck('subcategory.category_id')->toArray(),
        ]
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('data', 3)
                ->has(
                    'data.0',
                    fn (AssertableJson $json) => $json
                        ->where('id', $firstJob->id)
                        ->where('title', $firstJob->title)
                )
                ->has(
                    'data.2',
                    fn (AssertableJson $json) => $json
                        ->where('id', $lastJob->id)
                        ->where('title', $lastJob->title)
                )
        );
});
