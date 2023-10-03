<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AppVersions\IndexController;
use App\Models\Admin;
use App\Models\AppVersion;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;

test('Unauthenticated user cannot get app versions', function (): void {
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
    Sanctum::actingAs(Admin::factory()->create(), guard: 'admin');

    getJson(
        uri: action(IndexController::class),
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload', function (): void {
    Sanctum::actingAs(Admin::factory()->create(), guard: 'admin');

    $appVersion = AppVersion::factory()->create();

    getJson(
        uri: action(IndexController::class),
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->hasAll(['data', 'meta', 'links'])
                ->has('data', 1)
                ->has(
                    'data.0',
                    fn (AssertableJson $json) => $json
                        ->where('id', $appVersion->id)
                        ->where('buildNo', $appVersion->build_no)
                        ->where('version', $appVersion->version)
                        ->where('iosLink', $appVersion->ios_link)
                        ->where('androidLink', $appVersion->android_link)
                        ->where('isForcedUpdated', $appVersion->is_forced_updated)
                )
        );
});
