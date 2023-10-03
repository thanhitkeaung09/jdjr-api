<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Users\GetQuestionsController;
use App\Models\Question;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;
use function Pest\Laravel\withHeaders;

beforeEach(function (): void {
    withAppKeyHeaders();
});

test("If there are no app keys, it is not possible to retrieve my questions", function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    Sanctum::actingAs(User::factory()->create());

    getJson(
        uri: action(GetQuestionsController::class),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

test('If the app keys are outdated, it is not possible to retrieve my questions', function (): void {
    withAppKeyHeaders(true);

    Sanctum::actingAs(User::factory()->create());

    getJson(
        uri: action(GetQuestionsController::class),
    )
        ->assertStatus(Http::UPGRADE_REQUIRED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.outdated'))
                ->where('description', \trans('message.exceptions.invalid_app_keys'))
                ->where('status', Http::UPGRADE_REQUIRED->value)
        );
});

test('Unauthenicated user cannot retrieve my questions', function (): void {
    getJson(
        uri: action(GetQuestionsController::class),
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
        uri: action(GetQuestionsController::class),
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload when my questions are empty', function (): void {
    Sanctum::actingAs(User::factory()->create());

    getJson(
        uri: action(GetQuestionsController::class),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('data', [])
                ->whereType('data', 'array'),
        );
});

it('returns the correct payload', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    Question::factory(2)->favourited()->create([
        'user_id' => $user->id,
    ]);

    getJson(
        uri: action(GetQuestionsController::class),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('data', 2)
                ->has(
                    'data.0',
                    fn (AssertableJson $json) => $json
                        ->hasAll(['id', 'question', 'answer', 'isFavourited'])
                        ->whereAllType([
                            'id' => 'string',
                            'question' => 'string',
                            'answer' => 'string',
                            'isFavourited' => 'boolean',
                        ]),
                )
                ->has(
                    'data.1',
                    fn (AssertableJson $json) => $json
                        ->hasAll(['id', 'question', 'answer', 'isFavourited'])
                        ->whereAllType([
                            'id' => 'string',
                            'question' => 'string',
                            'answer' => 'string',
                            'isFavourited' => 'boolean',
                        ]),
                )
        );
});
