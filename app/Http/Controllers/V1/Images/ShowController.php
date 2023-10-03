<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Images;

use App\Services\FileStorage\FileStorage;
use Illuminate\Http\Response;

final class ShowController
{
    public function __construct(
        private readonly FileStorage $fileStorage,
    ) {
    }

    public function __invoke(string $path): Response
    {
        return $this->fileStorage->display($path);
    }
}
