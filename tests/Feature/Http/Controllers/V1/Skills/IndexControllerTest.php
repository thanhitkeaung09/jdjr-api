<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Skills\IndexController;
use App\Models\Category;
use App\Models\Job;
use App\Models\Skill;
use App\Models\Subcategory;
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

test('If the app keys are outdated, it is not possible to get categories', function (): void {
    withAppKeyHeaders(true);

    postJson(
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

it('returns the validation errors response, when the request input is invalid', function ($categories): void {
    postJson(
        uri: action(IndexController::class),
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

it('returns the correct status code', function (): void {
    Subcategory::factory()->count(3)->create();

    $job = Job::factory()->for(Subcategory::factory())->has(Skill::factory(3))->create();

    $categories = Category::query()->get()->pluck('id');

    postJson(
        uri: action(IndexController::class),
        data: [
            'categories' => [
                ...$categories,
                $job->subcategory->category_id,
            ]
        ]
    )->assertStatus(Http::OK->value);
});


it('returns the correct payload', function (): void {
    Subcategory::factory()->count(3)->create();
    Skill::factory(1)->create();

    $job = Job::factory()->has(Skill::factory(3))->create();

    $categories = Category::query()->get()->pluck('id');

    postJson(
        uri: action(IndexController::class),
        data: [
            'categories' => [
                ...$categories,
                $job->subcategory->category_id,
            ]
        ]
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('data', 3)
                ->has(
                    'data.0',
                    fn (AssertableJson $json) => $json
                        ->hasAll('id', 'name', 'icon')
                )
        );
});
