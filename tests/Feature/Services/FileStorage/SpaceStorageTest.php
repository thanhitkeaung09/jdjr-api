<?php

declare(strict_types=1);

use App\Services\FileStorage\SpaceStorage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

it('should upload an image from uploaded file', function (): void {
    Storage::fake();

    $path = (new SpaceStorage())->upload('images', UploadedFile::fake()->image('photo.jpg'));

    expect($path)->toBeString();

    Storage::assertExists($path);
});

it('should upload an image from link', function (): void {
    Storage::fake();

    $path = (new SpaceStorage())->upload('images', fake()->imageUrl(width: 10, height: 10));

    expect($path)->toBeString();

    Storage::assertExists($path);
});

it('should display an existing image', function (): void {
    Storage::fake();

    $storage = new SpaceStorage();

    $path = $storage->upload('images', fake()->imageUrl());

    Storage::assertExists($path);

    expect($storage->display($path)->status())->toBe(200);
});

it('should not display when an image does not exist', function (): void {
    Storage::fake();

    (new SpaceStorage())->display('test.png');
})->throws(NotFoundHttpException::class, 'File not found!');

it('should update an image from uploaded file', function (): void {
    Storage::fake();

    $storage = new SpaceStorage();

    $path = $storage->upload('images', UploadedFile::fake()->image('photo.jpg'));

    Storage::assertExists($path);

    $status = $storage->update(Arr::last(explode('/', $path)), fake()->imageUrl(width: 10, height: 10));

    expect($status)->toBeTrue();

    Storage::assertExists($path);
});

it('should return true when an image exists', function (): void {
    Storage::fake();

    $storage = new SpaceStorage();

    $path = $storage->upload('images', UploadedFile::fake()->image('photo.jpg'));

    expect($storage->exists($path))->toBeTrue();
});

it('should return false when an image does not exist', function (): void {
    Storage::fake();

    $storage = new SpaceStorage();

    expect($storage->exists('test.png'))->toBeFalse();
});
