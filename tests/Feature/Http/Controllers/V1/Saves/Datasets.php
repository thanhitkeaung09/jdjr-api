<?php

declare(strict_types=1);

dataset('validation_folders', [
    'folder id is null' => null,
    'folder id is number' => 12,
    'folder id is invalid uuid' => 'kfdjk2j32kj3k2',
]);

dataset('validation_savable_types', [
    'savable type is null' => null,
    'savable type is number' => 12,
    'savable type is invalid' => 'others',
]);

dataset('validation_savable_ids', [
    'savable id is null' => null,
    'savable id is number' => 12,
    'savable id is invalid uuid' => 'kfdjk2j32kj3k2',
]);
