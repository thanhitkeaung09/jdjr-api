<?php

declare(strict_types=1);

namespace App\Services\Firebase\Providers;

use App\Services\Firebase\FirebaseMessaging;
use App\Services\Firebase\Messaging;
use Illuminate\Support\ServiceProvider;

final class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * @var array<string, string>
     */
    public $bindings = [
        Messaging::class => FirebaseMessaging::class,
    ];
}
