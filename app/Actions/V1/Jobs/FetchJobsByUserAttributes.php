<?php

declare(strict_types=1);

namespace App\Actions\V1\Jobs;

use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

// FetchJobs
final readonly class FetchJobsByUserAttributes
{
    public function handle(User $user): Builder
    {
        $location = $user->location;
        $categories = $user->interests->pluck('id')->toArray();
        $skills = $user->skills->pluck('id')->toArray();

        return Job::query()
            ->with(['description'])
            ->with(['skills' => function ($query) use ($skills): void {
                $query->whereIn('id', $skills);
            }])
            ->when($location, function ($query, $location): void {
                $query->where('location_id', $location->id);
            })
            ->when(\count($categories) > 0, function ($query) use ($categories): void {
                $query->whereHas('subcategory', function ($q) use ($categories): void {
                    $q->whereIn('category_id', $categories);
                });
            })
            ->latest();
    }
}
