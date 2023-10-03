<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Users;

use App\DataObjects\V1\Users\NewUserData;
use App\Enums\Language;
use App\Http\Requests\Concerns\FailedValidation;
use App\Http\Requests\Concerns\PayloadRequestContract;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Experience;
use App\Models\Job;
use App\Models\Location;
use App\Models\Skill;
use App\Models\User;
use App\Rules\EmailUnique;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

final class UpdateRequest extends FormRequest implements PayloadRequestContract
{
    use FailedValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                new EmailUnique($this->user),
            ],
            'phone' => [
                'sometimes',
                'required',
                'string',
                'max:255'
            ],
            'password' => [
                'sometimes',
                'required',
                'string',
                'min:8',
                'max:255',
            ],
            'current_position' => [
                'sometimes',
                'nullable',
                'string',
                'uuid',
                Rule::exists(Job::class, 'id'),
            ],
            'device_token' => [
                'sometimes',
                'required',
                'string',
                'max:255'
            ],
            'language' => [
                'sometimes',
                'required',
                'string',
                Rule::in(Language::values())
            ],
            'profile' => [
                'sometimes',
                'nullable',
                File::image()->max(2 * 1024),
            ],
            'experience_id' => [
                'sometimes',
                'nullable',
                'string',
                'uuid',
                Rule::exists(Experience::class, 'id'),
            ],
            'location_id' => [
                'sometimes',
                'nullable',
                'string',
                'uuid',
                Rule::exists(Location::class, 'id'),
            ],
            'interests' => [
                'sometimes',
                'nullable',
                'array',
                'min:3',
                'max:5'
            ],
            'interests.*' => [
                'uuid',
                Rule::exists(Category::class, 'id'),
            ],
            'skills' => [
                'sometimes',
                'nullable',
                'array',
            ],
            'skills.*' => [
                'uuid',
                Rule::exists(Skill::class, 'id'),
            ],
        ];
    }

    private function getIgnore(): User
    {
        if (Auth::user() instanceof Admin) {
            return $this->user;
        }

        return Auth::user();
    }

    protected function passedValidation(): void
    {
        if (Auth::user() instanceof Admin) {
            $this->mergeIfMissing($this->user->toArray());
        } else {
            $this->mergeIfMissing(Auth::user()->toArray());
        }
    }

    public function payload(): NewUserData
    {
        return NewUserData::of(
            attributes: $this->toArray(),
        );
    }
}
