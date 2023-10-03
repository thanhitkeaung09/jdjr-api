<?php

declare(strict_types=1);

use Illuminate\Support\Str;

dataset('validation_emails', [
    'email does not exist in database' => 'notexists@gmail.com',
    'email is not string' => 12,
    'email format is wrong' => 'test',
    'email is null' => null,
    'email length is more than 255' => Str::random(256),
]);

dataset('validation_passwords', [
    'password is not string' => 12,
    'password is null' => null,
    'password length is less than 8' => Str::random(7),
    'password length is more than 255' => Str::random(256),
]);
