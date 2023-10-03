<?php

declare(strict_types=1);

namespace App\Http\Requests\Concerns;

use App\Http\Responses\ApiValidationErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait FailedValidation
{
    protected function failedValidation(Validator $validator): void
    {
        $response = new ApiValidationErrorResponse(
            title: \trans('message.exceptions.title.validation'),
            errors: $validator->errors(),
        );

        throw new HttpResponseException(
            response: $response->toResponse($this),
        );
    }
}
