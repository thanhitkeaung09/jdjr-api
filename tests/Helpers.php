<?php

declare(strict_types=1);

use App\Models\Admin;
use App\Models\ApplicationKey;
use App\Services\FileStorage\SpaceStorage;
use Illuminate\Http\Request;
use Illuminate\Testing\Fluent\AssertableJson;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\Sanctum;
use Mockery\MockInterface;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

use function Pest\Laravel\mock;
use function Pest\Laravel\withHeaders;

function errorAssertJson()
{
    return
        fn (AssertableJson $json) => $json
            ->where('title', 'Validation Error!')
            ->where('status', Http::UNPROCESSABLE_ENTITY->value)
            ->has('errors')
            ->whereType('errors', 'array');
}

function validationJsonStructure($key): array
{
    return [
        'title',
        'errors' => [$key],
        'status',
    ];
}

function getImageUrl(int $width = 5, int $height = 5): string
{
    return fake()->imageUrl(width: $width, height: $height);
}

function mockProfileUpload(string $image = 'test.jpg'): void
{
    mock(SpaceStorage::class, function (MockInterface $mock) use ($image): void {
        $mock->shouldReceive('upload')
            ->once()
            ->andReturn("profiles/{$image}");
    });
}

function mockProfileUploadNotCall(): void
{
    mock(SpaceStorage::class, function (MockInterface $mock): void {
        $mock->shouldNotHaveBeenCalled();
    });
}

function createRequest(string $method, string $uri, HeaderBag $headers): Request
{
    $symfonyRequest = SymfonyRequest::create(
        uri: $uri,
        method: $method,
    );

    $symfonyRequest->headers = $headers;

    return Request::createFromBase($symfonyRequest);
}

function withAppKeyHeaders(bool $obsoleted = false): void
{
    $appKey = ApplicationKey::factory()->create(['obsoleted' => $obsoleted]);

    withHeaders([
        'app-id' => $appKey->app_id,
        'app-secrete' => $appKey->app_secrete,
    ]);
}


function withAuthorizationHeader(string $token): void
{
    withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ]);
}

function superAdminLogin(): void
{
    $admin = Admin::factory()->create();
    $admin->assignRole(Role::create(['name' => 'Super Admin', 'guard_name' => 'admin']));
    Sanctum::actingAs($admin, guard: 'admin');
}
