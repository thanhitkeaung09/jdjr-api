<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

dataset('validation_names', [
    'name is not string' => 12,
    'name is null' => null,
    'name length is more than 255' => Str::random(256),
]);

dataset('validation_emails', [
    'email is null' => null,
    'email is not string' => 12,
    'email format is wrong' => 'test',
    'email length is more than 255' => Str::random(256),
]);

dataset('validation_phones', [
    'phone is null' => null,
    'phone is not string' => 12,
    'phone length is more than 255' => Str::random(256),
]);

dataset('validation_device_tokens', [
    'device_token is not string' => 12,
    'device_token length is more than 255' => Str::random(256),
]);

dataset('validation_languages', [
    'language is not string' => 12,
    'language is not en or mm' => 'fr',
]);

dataset('validation_profiles', [
    'profile is string' => 'test.png',
    'profile is url' => 'https://fake.com/test.png',
    'profile size is more than 2Mb' => UploadedFile::fake()->image('photo.jpg')->size(3 * 1024)
]);

dataset('validation_passwords', [
    'password is not string' => 12,
    'password is null' => null,
    'password length is less than 8' => Str::random(7),
    'password length is more than 255' => Str::random(256),
]);

dataset('validation_positions', [
    'position is not string' => 12,
    'position is not uuid' => '123456',
    'position id does not exist in the database' => Str::uuid()->toString(),
]);

dataset('validation_experiences', [
    'experience id is not string' => 12,
    'experience id is not uuid' => '123456',
    'experience id does not exist in the database' => Str::uuid()->toString(),
]);

dataset('validation_locations', [
    'location id is not string' => 12,
    'location id is not uuid' => '123456',
    'location id does not exist in the database' => '123456',
]);

dataset('validation_skills', [
    'skill is not array' => 'skill',
    'skill id is not uuid' => fn () => ['123456'],
    'skill id does not exist in the database' => fn () => ['123456'],
]);

dataset('validation_interests', [
    'interest is not array' => 'interest',
    'interest id is not uuid' => '123456',
    'interest id does not exist in the database' => '123456',
]);
