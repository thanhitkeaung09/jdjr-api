<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use App\Enums\Language;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * @property-read string $id
 * @property-read string $name
 * @property-read ?string $email
 * @property-read ?string $phone
 * @property-read ?string $profile
 * @property-read ?string $current_position
 * @property-read ?string $device_token
 * @property-read string $login_type
 * @property-read Language $language
 */
final class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'currentPosition' => $this->position ? new PositionResource(
                resource: $this->position
            ) : null,
            'profile' => $this->profile ?
                route(
                    name: 'api:v1:images:show',
                    parameters: [
                        'path' => $this->profile,
                    ],
                ) :
                $this->profile,
            'deviceToken' => $this->device_token,
            'loginType' => $this->login_type,
            'language' => $this->language->value,
            'experience' => new UserExperienceResource(
                resource: $this->whenLoaded('experience'),
            ),
            'location' => $this->when(
                condition: $this->location instanceof Location,
                value: fn () => new LocationResource($this->location),
                default: LocationResource::nullObj(),
            ),
            'interests' => CategoryResource::collection(
                resource: $this->whenLoaded('interests'),
            ),
            'skills' => SkillResource::collection(
                resource: $this->whenLoaded('skills'),
            ),
            'folders' => FolderResource::collection(
                resource: $this->whenLoaded('folders'),
            ),
            'usersCount' => $this->when(Auth::user() instanceof User, User::query()->count())
        ];
    }
}
