<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Jobs;

use App\DataObjects\V1\Jobs\NewJob;
use App\Http\Requests\Concerns\FailedValidation;
use App\Http\Requests\Concerns\PayloadRequestContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

final class StoreRequest extends FormRequest implements PayloadRequestContract
{
    use FailedValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'subcategory_id' => [
                'required',
                'string',
                'uuid',
                Rule::exists('subcategories', 'id'),
            ],
            'location_id' => [
                'required',
                'string',
                'uuid',
                Rule::exists('locations', 'id'),
            ],
            'description_title' => [
                'required',
                'string',
                'max:255',
            ],
            'description_body' => [
                'required',
                'string',
                "min:350",
            ],
            'image' => [
                'sometimes',
                'required',
                'nullable',
                File::image()->max(2 * 1024 * 1024)->types(['png', 'jpg', 'jpeg']),
            ],
            'tools_remark' => [
                'required',
                'nullable',
                'string',
                'max:255',
            ],
            'career_paths' => [
                'required',
                'array',
            ],
            'experiences' => [
                'required',
                'array',
            ],
            'qualifications' => [
                'present',
                'array',
            ],
            'qualifications.*.icon' => [
                'sometimes',
                File::image()->types(['svg']),
            ],
            'qualifications.*.text' => [
                'sometimes',
                'required',
                'max:100'
            ],
            'responsibilities' => [
                'required',
                'array',
            ],
            'responsibilities.*.icon' => [
                File::image()->types(['svg']),
            ],
            'responsibilities.*.text' => [
                'required',
            ],
            'skills' => [
                'required',
                'array',
            ],
            'tools' => [
                'required',
                'array',
            ],
        ];
    }

    public function payload(): NewJob
    {
        return NewJob::of($this->validated());
    }
}
