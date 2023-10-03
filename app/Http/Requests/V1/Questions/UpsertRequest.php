<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Questions;

use App\DataObjects\V1\Questions\NewQuestion;
use App\Http\Requests\Concerns\FailedValidation;
use App\Http\Requests\Concerns\PayloadRequestContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpsertRequest extends FormRequest implements PayloadRequestContract
{
    use FailedValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "job_id" => [
                'required',
                'string',
                'uuid',
                Rule::exists('jobs', 'id'),
            ],
            "user_id" => [
                'required',
                'string',
                'uuid',
                Rule::exists('users', 'id'),
            ],
            "question" => [
                'required',
                'string',
                'max:255',
            ],
            "answer" => [
                'sometimes',
                'required',
                'string',
            ],
            "is_favourited" => [
                'sometimes',
                'required',
                'boolean',
            ],
        ];
    }

    public function payload(): NewQuestion
    {
        return NewQuestion::of([
            'job_id' => $this->string('job_id')->toString(),
            'user_id' => $this->string('user_id')->toString(),
            'question' => $this->string('question')->toString(),
            'answer' => $this->validated('answer'),
            "is_favourited" => $this->boolean('is_favourited'),
        ]);
    }
}
