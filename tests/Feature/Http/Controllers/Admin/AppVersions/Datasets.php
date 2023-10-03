<?php

declare(strict_types=1);

use Illuminate\Support\Str;

dataset('validation_versions', [
    'version is null' => null,
    'version is not string' => 12,
    'version length is more than 255' => Str::random(256),
]);

dataset('validation_build_nos', [
    'build no is null' => null,
    'build no is not string' => 12,
    'build no length is more than 255' => Str::random(256),
]);

dataset('validation_is_forced_updated', [
    'is_forced_updated is null' => null,
    'is_forced_updated is not boolean' => 'string',
]);

dataset('validation_ios_links', [
    'ios link is null' => null,
    'ios link is not string' => 12,
    'ios link length is more than 255' => Str::random(256),
]);

dataset('validation_android_links', [
    'android link is null' => null,
    'android link is not string' => 12,
    'android link length is more than 255' => Str::random(256),
]);
