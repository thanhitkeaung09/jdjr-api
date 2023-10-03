<?php

declare(strict_types=1);

use App\Events\NewQuestionEvent;
use App\Http\Controllers\V1\Questions\StoreController;
use App\Models\Job;
use App\Models\Question;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;
use function Pest\Laravel\withHeaders;

beforeEach(function (): void {
    withAppKeyHeaders();
});

test("If there are no app keys, it is not possible to store question", function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    Sanctum::actingAs(User::factory()->create());

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

test('If the app keys are outdated, it is not possible to store question', function (): void {
    withAppKeyHeaders(true);

    Sanctum::actingAs(User::factory()->create());

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

test('Unauthenicated user cannot store question', function (): void {
    postJson(
        uri: action(StoreController::class),
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
    Sanctum::actingAs($user = User::factory()->create());
    $job = Job::factory()->create();
    Event::fake();

    postJson(
        uri: action(StoreController::class),
        data: [
            'job_id' => $job->id,
            'user_id' => $user->id,
            'question' => 'What is ?'
        ],
    )
        ->assertStatus(Http::OK->value);

    Event::assertDispatched(NewQuestionEvent::class, 1);
});

it('returns the correct payload', function (): void {
    Sanctum::actingAs($user = User::factory()->create());
    $job = Job::factory()->create();
    Event::fake();

    postJson(
        uri: action(StoreController::class),
        data: [
            'job_id' => $job->id,
            'user_id' => $user->id,
            'question' => 'What is ?'
        ]
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('id')
                ->where('question', 'What is ?')
                ->where('answer', null)
                ->where('isFavourited', false),
        );

    assertDatabaseHas('questions', [
        'user_id' => $user->id,
        'job_id' => $job->id,
    ]);

    Event::assertDispatched(NewQuestionEvent::class, 1);
    Event::assertDispatched(NewQuestionEvent::class, function ($e) {
        $question = Question::query()->first();
        return $e->question->is($question);
    });
});
