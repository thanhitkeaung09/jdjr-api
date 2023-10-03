<?php

declare(strict_types=1);

namespace App\Actions\V1\Users;

use App\DataObjects\V1\Users\NewUserData;
use App\Models\User;
use App\Services\FileStorage\FileStorage;
use Illuminate\Http\UploadedFile;

final readonly class UpdateUser
{
    public function __construct(
        private FileStorage $fileStorage,
    ) {
    }

    public function handle(User $user, NewUserData $data): bool
    {
        $attributes = $data->toArray();

        if ($data->profile && $data->profile instanceof UploadedFile) {
            $attributes['profile'] = $this->updateProfile(
                oldPath: $user->profile,
                file: $data->profile,
            );
        }

        if ($data->skills) {
            $this->updateSkills($user, $data->skills);
        }

        if ($data->interests) {
            $this->updateInterests($user, $data->interests);
        }

        return $user->update(
            attributes: $attributes,
        );
    }

    private function updateProfile(?string $oldPath, UploadedFile $file): string
    {
        $this->fileStorage->delete($oldPath);

        return $this->fileStorage->upload(
            folder: \strval(\config('folders.profiles')),
            file: $file
        );
    }

    private function updateSkills(User $user, array $skills): void
    {
        $user->skills()->sync($skills);
    }

    private function updateInterests(User $user, array $interests): void
    {
        $user->interests()->sync($interests);
    }
}
