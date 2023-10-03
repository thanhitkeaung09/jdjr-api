<?php

declare(strict_types=1);

use App\Http\Middleware\CheckApplicationKey;
use App\Models\ApplicationKey;
use JustSteveKing\StatusCode\Http;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Response;

it('returns the forbidden response without app keys in headers', function (): void {
    $appKey = ApplicationKey::factory()->create();

    $headers = new HeaderBag([]);

    $response = (new CheckApplicationKey())->handle(
        request: createRequest('get', '/', $headers),
        next: fn () => new Response(),
    );

    expect($response->isForbidden())->toBeTrue();
});

it('returns the upgrade required response with obsoleted app keys', function (): void {
    $appKey = ApplicationKey::factory()->obsoleted()->create();

    $headers = new HeaderBag([
        'app-id' => $appKey->app_id,
        'app-secrete' => $appKey->app_secrete,
    ]);

    $response = (new CheckApplicationKey())->handle(
        request: createRequest('get', '/', $headers),
        next: fn () => new Response(),
    );

    expect($response->getStatusCode())->toBe(Http::UPGRADE_REQUIRED->value);
});

it('returns the correct response with appropriate app keys', function (): void {
    $appKey = ApplicationKey::factory()->create();

    $headers = new HeaderBag([
        'app-id' => $appKey->app_id,
        'app-secrete' => $appKey->app_secrete,
    ]);

    $response = (new CheckApplicationKey())->handle(
        request: createRequest('get', '/', $headers),
        next: fn () => new Response(),
    );

    expect($response->isOk())->toBeTrue();
});
