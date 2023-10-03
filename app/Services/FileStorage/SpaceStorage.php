<?php

declare(strict_types=1);

namespace App\Services\FileStorage;

use Exception;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Flysystem\UnableToCheckDirectoryExistence;
use League\Flysystem\UnableToCheckFileExistence;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class SpaceStorage implements FileStorage
{
    public function clearCache(string $folder, string $fileName): ClientResponse
    {
        return Http::asJson()->delete(
            config('filesystems.space.cdn_endpoint') . '/cache',
            [
                'files' => ["{$folder}/{$fileName}"],
            ]
        );
    }

    public function display(string $path): HttpResponse
    {
        if ( ! $this->exists($path)) {
            throw new NotFoundHttpException('File not found!');
        }
        return $this->makeFileResponse($path);
    }

    public function put(string $folder, string $link): string
    {
        $name = $this->generateFileName();

        Storage::put(
            path: $folder . '/' . $name,
            contents: $this->getContents($link),
        );

        return $folder . '/' . $name;
    }

    public function upload(string $folder, UploadedFile|string $file): string
    {
        if ($file instanceof UploadedFile) {
            return $this->putFileAs(
                folder: $folder,
                file: $file,
                name: $this->generateFileName($file),
            );
        }

        return $this->put($folder, $file);
    }

    public function update(string $oldPath, string $link): bool
    {
        return Storage::put(
            path: $oldPath,
            contents: $this->getContents($link),
        );
    }

    public function delete(?string $path): bool
    {
        if ($path && $this->exists($path)) {
            return Storage::delete($path);
        }

        return false;
    }

    public function exists(string $path): bool
    {
        try {
            return Storage::exists($path);
        } catch (UnableToCheckDirectoryExistence) {
            return false;
        } catch (UnableToCheckFileExistence) {
            return false;
        }
    }

    private function getContents(string $link): string
    {
        if ( ! ($contents = file_get_contents($link))) {
            throw new Exception("Could not load file from link {$link}");
        }

        return $contents;
    }

    private function putFileAs(string $folder, UploadedFile $file, string $name): string
    {
        $path = Storage::putFileAs(
            path: $folder,
            file: $file,
            name: $name,
        );

        if ( ! $path) {
            throw new Exception('File does not upload!');
        }

        return $path;
    }

    private function makeFileResponse(string $path): HttpResponse
    {
        $file = Storage::get($path);
        $type = Storage::mimeType($path);
        $response = Response::make($file, 200);

        if (false === $type) {
            throw new Exception('File type does not support!');
        }

        return $response->header('Content-Type', $type)->setMaxAge(604800)->setPrivate();
    }

    private function generateFileName(?UploadedFile $file = null): string
    {
        if (null === $file) {
            return (string) Str::uuid() . '.png';
        }
        return (string) Str::uuid() . '.' . $file->getClientOriginalExtension();
    }
}
