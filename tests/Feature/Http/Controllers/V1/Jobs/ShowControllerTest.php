<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Jobs\ShowController;
use App\Models\CareerPath;
use App\Models\Description;
use App\Models\Experience;
use App\Models\Folder;
use App\Models\Job;
use App\Models\Qualification;
use App\Models\Question;
use App\Models\Responsibility;
use App\Models\Skill;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;
use function Pest\Laravel\withHeaders;

beforeEach(function (): void {
    withAppKeyHeaders();
});

test('If there are no app keys, it is not possible to show job details', function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    Sanctum::actingAs(User::factory()->create());
    $job = Job::factory()->create();

    getJson(
        uri: action(ShowController::class, ['job' => $job]),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

test('If the app keys are outdated, it is not possible to show job details', function (): void {
    withAppKeyHeaders(true);

    Sanctum::actingAs(User::factory()->create());
    $job = Job::factory()->create();

    getJson(
        uri: action(ShowController::class, ['job' => $job]),
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
    $job = Job::factory()->create();

    getJson(
        uri: action(ShowController::class, ['job' => $job]),
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
        uri: action(ShowController::class, ['job' => $job]),
    )
        ->assertStatus(Http::OK->value);
});

it('returns the not found exception when not found', function (): void {
    Sanctum::actingAs(User::factory()->create());

    getJson(
        uri: action(ShowController::class, ['job' => 'fdkja-fdajfkad-dfjadjfka']),
    )
        ->assertStatus(Http::NOT_FOUND->value);
});

it('returns the correct payload', function (): void {
    Sanctum::actingAs($user = User::factory()->create());

    $job = Job::factory()
        ->has(Description::factory())
        ->has(Question::factory()->favourited())
        ->has(Qualification::factory())
        ->has(Responsibility::factory())
        ->hasAttached(Skill::factory(), relationship: 'skills')
        ->hasAttached(Tool::factory(), relationship: 'tools')
        ->hasAttached(Folder::factory(), pivot: ['user_id' => $user->id], relationship: 'folders')
        ->has(CareerPath::factory())
        ->hasAttached(Experience::factory(), pivot: [
            'position_name' => 'Junior Developer',
            'range' => '100K ~ 300K',
        ], relationship: 'experiences')
        ->create();

    $skill = Skill::query()->first();

    getJson(
        uri: action(ShowController::class, ['job' => $job]),
    )
        ->assertStatus(Http::OK->value)
        ->dump()
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('id', $job->id)
                ->where('title', $job->title)
                ->where('image', route('api:v1:images:show', ['path' => $job->icon]))
                ->hasAll(['created', 'updated'])
                ->whereAllType([
                    'created' => 'array',
                    'updated' => 'array',
                ])
                ->has(
                    'description',
                    fn (AssertableJson $json) => $json
                        ->hasAll(['id', 'title', 'body'])
                        ->whereAllType([
                            'id' => 'string',
                            'title' => 'string',
                            'body' => 'string',
                        ])
                )
                ->has('questions', 1)
                ->has(
                    'questions.0',
                    fn (AssertableJson $json) => $json
                        ->hasAll(['id', 'question', 'answer', 'isFavourited'])
                        ->whereAllType([
                            'id' => 'string',
                            'question' => 'string',
                            'answer' => 'string',
                            'isFavourited' => 'boolean'
                        ])
                )
                ->has('qualifications', 1)
                ->has(
                    'qualifications.0',
                    fn (AssertableJson $json) => $json
                        ->hasAll(['id', 'icon', 'text'])
                        ->whereAllType([
                            'id' => 'string',
                            'icon' => 'string',
                            'text' => 'string',
                        ])
                )
                ->has('responsibilities', 1)
                ->has(
                    'responsibilities.0',
                    fn (AssertableJson $json) => $json
                        ->hasAll(['id', 'icon', 'text'])
                        ->whereAllType([
                            'id' => 'string',
                            'icon' => 'string',
                            'text' => 'string',
                        ])
                )
                ->has('skills', 1)
                ->has(
                    'skills.0',
                    fn (AssertableJson $json) => $json
                        ->hasAll(['id', 'name', 'icon'])
                        ->whereAllType([
                            'id' => 'string',
                            'name' => 'string',
                            'icon' => 'string',
                        ])
                )
                ->where('skillsMatch', '0 out of 1 match with your profile')
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
                ->has('tools', 1)
                ->has(
                    'tools.0',
                    fn (AssertableJson $json) => $json
                        ->hasAll(['id', 'name', 'icon'])
                        ->whereAllType([
                            'id' => 'string',
                            'name' => 'string',
                            'icon' => 'string',
                        ])
                )
                ->has('careerPaths', 1)
                ->has(
                    'careerPaths.0',
                    fn (AssertableJson $json) => $json
                        ->hasAll(['id', 'title', 'body'])
                        ->whereAllType([
                            'id' => 'string',
                            'title' => 'string',
                            'body' => 'string',
                        ])
                )
                ->has('experiences', 1)
                ->has(
                    'experiences.0',
                    fn (AssertableJson $json) => $json
                        ->hasAll(['id', 'duration', 'salary', 'position'])
                        ->whereAllType([
                            'id' => 'string',
                            'duration' => 'string',
                            'salary' => 'string',
                            'position' => 'string',
                        ])
                ),
        );
});
