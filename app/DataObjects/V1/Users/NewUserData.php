<?php

declare(strict_types=1);

namespace App\DataObjects\V1\Users;

use App\Enums\Language;
use App\Enums\LoginType;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Thuraaung\MakeFiles\Contracts\DataObjectContract;

final readonly class NewUserData implements DataObjectContract
{
    public function __construct(
        public string $name,
        public ?string $email,
        public ?string $password,
        public LoginType $loginType,
        public ?string $loginId,
        public ?string $currentPosition,
        public ?string $experienceId,
        public ?string $locationId,
        public array|null $interests,
        public array|null $skills,
        public UploadedFile|string|null $profile = null,
        public ?string $phone = null,
        public ?string $deviceToken = null,
        public ?Language $language = Language::EN,
    ) {
    }

    /**
     * @param array{name:string,email?:string,password?:string,login_type:string,login_id?:string,current_position?:string,experience_id?:string,location_id?:string,interests?:array,skills?:array,profile?:UploadedFile,phone?:string,device_token?:string,language?:string} $attributes
     *
     * @return NewUserData
     */
    public static function of(array $attributes): NewUserData
    {
        return new self(
            name: $attributes['name'],
            email: $attributes['email'],
            password: $attributes['password'],
            loginType: LoginType::from($attributes['login_type']),
            loginId: $attributes['login_id'],
            currentPosition: $attributes['current_position'],
            experienceId: $attributes['experience_id'],
            locationId: $attributes['location_id'],
            interests: Arr::exists($attributes, 'interests') ? $attributes['interests'] : null,
            skills: Arr::exists($attributes, 'skills') ? $attributes['skills'] : null,
            profile: $attributes['profile'],
            phone: $attributes['phone'],
            deviceToken: $attributes['device_token'],
            language: Language::from($attributes['language']),
        );
    }

    /**
     * @return array{name:string,email?:string,password?:string,login_type:string,login_id?:string,current_position?:string,experience_id?:string,location_id?:string,phone?:string,device_token?:string,language?:string}
     */
    public function toArray(): array
    {
        $password = null;

        if (null !== $this->password) {
            $password = Hash::info($this->password)['algo'] ? $this->password : Hash::make($this->password);
        }

        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $password,
            'phone' => $this->phone,
            'profile' => $this->profile,
            'login_type' => $this->loginType->value,
            'login_id' => $this->loginId,
            'current_position' => $this->currentPosition,
            'experience_id' => $this->experienceId,
            'location_id' => $this->locationId,
            'device_token' => $this->deviceToken,
            'language' => $this->language?->value,
        ];
    }
}
