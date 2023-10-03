<?php

declare(strict_types=1);

use Illuminate\Support\Str;

dataset('validation_names', [
    'version is null' => null,
    'version is not string' => 12,
    'version length is more than 255' => Str::random(256),
]);
