<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Jobs\RelatedJobsController;
use App\Models\Description;
use App\Models\Job;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;
use function Pest\Laravel\withHeaders;

beforeEach(function (): void {
    withAppKeyHeaders();
});

test('If there are no app keys, it is not possible to get related jobs', function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    Sanctum::actingAs(User::factory()->create());
    $job = Job::factory()->create();

    getJson(
        uri: action(RelatedJobsController::class, ['job' => $job]),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

test('If the app keys are outdated, it is not possible to get related jobs', function (): void {
    withAppKeyHeaders(true);

    Sanctum::actingAs(User::factory()->create());
    $job = Job::factory()->create();

    getJson(
        uri: action(RelatedJobsController::class, ['job' => $job]),
    )
        ->assertStatus(Http::UPGRADE_REQUIRED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.outdated'))
                ->where('description', \trans('message.exceptions.invalid_app_keys'))
                ->where('status', Http::UPGRADE_REQUIRED->value)
        );
});

test('Unauthenicated user cannot get related jobs', function (): void {
    $job = Job::factory()->create();

    getJson(
        uri: action(RelatedJobsController::class, ['job' => $job]),
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
    $job = Job::factory()->create();

    getJson(
        uri: action(RelatedJobsController::class, ['job' => $job]),
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload when related jobs are empty', function (): void {
    Sanctum::actingAs(User::factory()->create());
    $job = Job::factory()->create();

    getJson(
        uri: action(RelatedJobsController::class, ['job' => $job]),
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

    $job = Job::factory()->for(Subcategory::factory())->create();

    $subcategory = Subcategory::query()->first();

    $jobs = Job::factory(2)
        ->has(Description::factory())
        ->create(['subcategory_id' => $subcategory->id]);

    getJson(
        uri: action(RelatedJobsController::class, ['job' => $job]),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('data', 2)
                ->has(
                    'data.0',
                    fn (AssertableJson $json) => $json
                        ->where('id', $jobs->first()->id)
                        ->where('title', $jobs->first()->title)
                        ->where('image', route('api:v1:images:show', ['path' => $jobs->first()->icon]))
                        ->hasAll(['created', 'updated'])
                        ->has(
                            'description',
                            fn (AssertableJson $json) => $json
                                ->where('id', $jobs->first()->description->id)
                                ->where('title', $jobs->first()->description->title)
                                ->where('body', $jobs->first()->description->body)
                        )
                        ->where('toolsRemark', $jobs->first()->tools_remark)
                        ->where('saved', false)
                        ->where('savable', null)
                        ->whereAllType([
                            'created' => 'array',
                            'updated' => 'array',
                        ]),
                ),
        );
});
