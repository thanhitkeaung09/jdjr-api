<?php

declare(strict_types=1);

dataset('validation_categories', [
    'categories are not array' => 'string',
    'categories are null' => null,
    'categories length is less than 3' => fn () => ['1', '2'],
    'categories length is greater than 5' => fn () => ['1', '2', '3', '4', '5', '6'],
]);
