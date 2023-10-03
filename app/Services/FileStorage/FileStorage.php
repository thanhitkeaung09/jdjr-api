<?php

declare(strict_types=1);

namespace App\Services\FileStorage;

use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;

interface FileStorage
{
    public function clearCache(string $folder, string $fileName): ClientResponse;

    public function display(string $path): Response;

    public function put(string $folder, string $link): string;

    public function upload(string $folder, UploadedFile|string $file): string;

    public function update(string $oldPath, string $link): bool;

    public function delete(?string $path): bool;

    public function exists(string $path): bool;
}
