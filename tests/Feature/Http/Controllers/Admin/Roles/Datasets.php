<?php

declare(strict_types=1);

use Illuminate\Support\Str;

dataset('validation_names', [
    'name is null' => null,
    'name is not string' => 12,
    'name length is more than 255' => Str::random(256),
]);

dataset('validation_permissions', [
    'permission is null' => null,
    'permission is not array' => 'permission',
]);
