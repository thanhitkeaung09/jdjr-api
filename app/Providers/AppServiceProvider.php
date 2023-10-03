<?php

declare(strict_types=1);

namespace App\Providers;

use App\Enums\SavableType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Model::shouldBeStrict(
            shouldBeStrict: true,
        );

        Model::preventLazyLoading(false);

        // Relation::morphMap([
        //     SavableType::NEWS->value => SavableType::NEWS->toModelString(),
        //     SavableType::JOB->value => SavableType::JOB->toModelString(),
        // ]);
    }
}
