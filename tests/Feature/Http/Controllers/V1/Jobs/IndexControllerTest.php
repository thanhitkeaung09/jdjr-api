<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Jobs\IndexController;
use App\Models\Description;
use App\Models\Job;
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

test('If there are no app keys, it is not possible to get users jobs', function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    Sanctum::actingAs(User::factory()->create());

    getJson(
        uri: action(IndexController::class),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

test('If the app keys are outdated, it is not possible to get user jobs', function (): void {
    withAppKeyHeaders(true);

    Sanctum::actingAs(User::factory()->create());

    getJson(
        uri: action(IndexController::class),
    )
        ->assertStatus(Http::UPGRADE_REQUIRED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.outdated'))
                ->where('description', \trans('message.exceptions.invalid_app_keys'))
                ->where('status', Http::UPGRADE_REQUIRED->value)
        );
});

test('Unauthenicated user cannot get user jobs', function (): void {
    getJson(
        uri: action(IndexController::class),
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
        uri: action(IndexController::class),
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload when jobs are empty', function (): void {
    Sanctum::actingAs(User::factory()->create());

    getJson(
        uri: action(IndexController::class),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('data', [])
                ->whereType('data', 'array')
                ->hasAll('links', 'meta'),
        );
});

it('returns the correct payload', function (): void {
    $user = User::factory()
        ->hasAttached(Skill::factory(), relationship: 'skills')
        ->create();

    Sanctum::actingAs($user);

    $job = Job::factory()
        ->has(Description::factory())
        ->create();

    $job->skills()->attach($skill = Skill::query()->first());

    getJson(
        uri: action(IndexController::class),
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
                        ->has(
                            'description',
                            fn (AssertableJson $json) => $json
                                ->where('id', $job->description->id)
                                ->where('title', $job->description->title)
                                ->where('body', $job->description->body)
                        )
                        ->has('skills', 1)
                        ->has(
                            'skills.0',
                            fn (AssertableJson $json) => $json
                                ->where('id', $skill->id)
                                ->where('name', $skill->name)
                                ->where('icon', route('api:v1:images:show', ['path' => $skill->icon]))
                        )
                        ->where('skillsMatch', '1 out of 1 match with your profile')
                        ->where('toolsRemark', $job->tools_remark)
                        ->where('saved', false)
                        ->has('savable')
                        ->whereAllType([
                            'savable' => 'null',
                            'created' => 'array',
                            'updated' => 'array',
                        ]),
                )
                ->hasAll('links', 'meta'),
        );
});
