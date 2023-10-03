<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Images\ShowController;
use App\Services\FileStorage\SpaceStorage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\getJson;

it('returns the correct status code', function (): void {
    Storage::fake();

    $storage = new SpaceStorage();

    $path = $storage->upload('profiles', UploadedFile::fake()->image('photo.jpg'));

    Storage::assertExists($path);

    getJson(
        uri: action(ShowController::class, ['path' => $path]),
    )
        ->assertOk();
});

it('returns the 404 status code when file does not exist', function (): void {
    getJson(
        uri: action(ShowController::class, ['path' => '/profiles/test.jpg']),
    )
        ->assertNotFound();
});
