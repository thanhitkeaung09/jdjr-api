<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Http\Responses\ApiErrorResponse;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use JustSteveKing\StatusCode\Http;

final class EmailVerifyException extends Exception
{
    public function render(): Responsable|bool
    {
        if (request()->isJson()) {
            return new ApiErrorResponse(
                title: \trans('message.exceptions.title.email_not_verified'),
                description: $this->message,
                status: Http::from($this->code),
            );
        }

        return false;
    }
}
