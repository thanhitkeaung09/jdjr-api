<?php

declare(strict_types=1);

namespace App\Actions\V1\Users;

use Illuminate\Contracts\Auth\Factory;
use Illuminate\Database\Eloquent\Builder;

final readonly class FetchMyQuestions
{
    public function __construct(
        private Factory $auth
    ) {
    }

    public function handle(): Builder
    {
        return $this->auth->guard()->user()
            ->questions()
            ->whereNotNull('answer')
            ->where('is_favourited', true)
            ->latest()
            ->getQuery();
    }
}
