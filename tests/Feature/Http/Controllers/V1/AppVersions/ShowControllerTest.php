<?php

declare(strict_types=1);

use App\Http\Controllers\V1\AppVersions\ShowController;
use App\Models\AppVersion;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;

use function Pest\Laravel\getJson;
use function Pest\Laravel\withHeaders;

beforeEach(function (): void {
    withAppKeyHeaders();
});

test('If there are no app keys, it is not possible to get app version', function (): void {
    withHeaders([
        'app-id' => null,
        'app-secrete' => null,
    ]);

    getJson(
        uri: action(ShowController::class),
    )
        ->assertStatus(Http::FORBIDDEN->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthorized'))
                ->where('description', \trans('message.exceptions.permission_denied'))
                ->where('status', Http::FORBIDDEN->value)
        );
});

test('If the app keys are outdated, it is not possible to get app version', function (): void {
    withAppKeyHeaders(true);

    getJson(
        uri: action(ShowController::class),
    )
        ->assertStatus(Http::UPGRADE_REQUIRED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.outdated'))
                ->where('description', \trans('message.exceptions.invalid_app_keys'))
                ->where('status', Http::UPGRADE_REQUIRED->value)
        );
});

it('returns the not found exception when a resource is not found', function (): void {
    getJson(
        uri: action(ShowController::class),
    )
        ->assertStatus(Http::NOT_FOUND->value);
});

it('returns the correct status code', function (): void {
    AppVersion::factory()->create();

    getJson(
        uri: action(ShowController::class),
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload', function (): void {
    $appVersion = AppVersion::factory()->create();

    getJson(
        uri: action(ShowController::class),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('id', $appVersion->id)
                ->where('version', $appVersion->version)
                ->where('buildNo', $appVersion->build_no)
                ->where('isForcedUpdated', $appVersion->is_forced_updated)
                ->where('iosLink', $appVersion->ios_link)
                ->where('androidLink', $appVersion->android_link)
        );
});
