<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AppVersions\UpdateController;
use App\Models\Admin;
use App\Models\AppVersion;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\putJson;

test('Unauthenticated user cannot update app version', function (): void {
    $appVersion = AppVersion::factory()->create();

    putJson(
        uri: action(UpdateController::class, ['appVersion' => $appVersion]),
    )
        ->assertStatus(Http::UNAUTHORIZED->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', \trans('message.exceptions.title.unauthenicated'))
                ->where('description', 'Unauthenticated.')
                ->where('status', Http::UNAUTHORIZED->value)
        );
});

it('returns the not found status code when a resource is not found', function (): void {
    Sanctum::actingAs(Admin::factory()->create(), guard: 'admin');

    putJson(
        uri: action(UpdateController::class, ['appVersion' => 'djfkdjfkjkwekwjekw']),
    )
        ->assertStatus(Http::NOT_FOUND->value);
});

it('returns the validation error when an input version field is invalid', function ($version): void {
    Sanctum::actingAs(Admin::factory()->create(), guard: 'admin');
    $appVersion = AppVersion::factory()->create();
    $newVersion = AppVersion::factory()->make()->toArray();
    putJson(
        uri: action(UpdateController::class, ['appVersion' => $appVersion]),
        data: [
            ...$newVersion,
            'version' => $version,
        ],
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('version'));
})->with('validation_versions');

it('returns the validation error when an input build no field is invalid', function ($buildNo): void {
    Sanctum::actingAs(Admin::factory()->create(), guard: 'admin');
    $appVersion = AppVersion::factory()->create();
    $newVersion = AppVersion::factory()->make()->toArray();
    putJson(
        uri: action(UpdateController::class, ['appVersion' => $appVersion]),
        data: [
            ...$newVersion,
            'build_no' => $buildNo,
        ],
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('build_no'));
})->with('validation_build_nos');

it('returns the validation error when an input is_forced_updated field is invalid', function ($isForcedUpdated): void {
    Sanctum::actingAs(Admin::factory()->create(), guard: 'admin');
    $appVersion = AppVersion::factory()->create();
    $newVersion = AppVersion::factory()->make()->toArray();
    putJson(
        uri: action(UpdateController::class, ['appVersion' => $appVersion]),
        data: [
            ...$newVersion,
            'is_forced_updated' => $isForcedUpdated,
        ],
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('is_forced_updated'));
})->with('validation_is_forced_updated');

it('returns the validation error when an input ios_link field is invalid', function ($iosLink): void {
    Sanctum::actingAs(Admin::factory()->create(), guard: 'admin');
    $appVersion = AppVersion::factory()->create();
    $newVersion = AppVersion::factory()->make()->toArray();
    putJson(
        uri: action(UpdateController::class, ['appVersion' => $appVersion]),
        data: [
            ...$newVersion,
            'ios_link' => $iosLink,
        ],
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('ios_link'));
})->with('validation_ios_links');

it('returns the validation error when an input android_link field is invalid', function ($androidLink): void {
    Sanctum::actingAs(Admin::factory()->create(), guard: 'admin');
    $appVersion = AppVersion::factory()->create();
    $newVersion = AppVersion::factory()->make()->toArray();
    putJson(
        uri: action(UpdateController::class, ['appVersion' => $appVersion]),
        data: [
            ...$newVersion,
            'android_link' => $androidLink,
        ],
    )
        ->assertStatus(Http::UNPROCESSABLE_ENTITY->value)
        ->assertJson(errorAssertJson())
        ->assertJsonStructure(validationJsonStructure('android_link'));
})->with('validation_android_links');

it('returns the correct status code', function (): void {
    Sanctum::actingAs(Admin::factory()->create(), guard: 'admin');

    $appVersion = AppVersion::factory()->create();
    $newVersion = AppVersion::factory()->make()->toArray();

    putJson(
        uri: action(UpdateController::class, ['appVersion' => $appVersion]),
        data: $newVersion
    )
        ->assertStatus(Http::OK->value);
});

it('returns the correct payload', function (): void {
    Sanctum::actingAs(Admin::factory()->create(), guard: 'admin');

    $appVersion = AppVersion::factory()->create();
    $newVersion = AppVersion::factory()->make()->toArray();

    putJson(
        uri: action(UpdateController::class, ['appVersion' => $appVersion]),
        data: $newVersion,
    )
        ->assertStatus(Http::OK->value)
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('message', \trans('message.update.success'))
        );

    assertDatabaseHas('app_versions', $newVersion);
});
