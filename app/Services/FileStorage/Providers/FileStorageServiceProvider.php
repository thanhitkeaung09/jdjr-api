<?php

declare(strict_types=1);

namespace App\Services\FileStorage\Providers;

use App\Services\FileStorage\FileStorage;
use App\Services\FileStorage\SpaceStorage;
use Illuminate\Support\ServiceProvider;

final class FileStorageServiceProvider extends ServiceProvider
{
    /**
     * @var array<string, string>
     */
    public $bindings = [
        FileStorage::class => SpaceStorage::class,
    ];
}
