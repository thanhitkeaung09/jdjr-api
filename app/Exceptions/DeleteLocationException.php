<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Http\Responses\ApiErrorResponse;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use JustSteveKing\StatusCode\Http;

final class DeleteLocationException extends Exception
{
    public function render(): Responsable
    {
        return new ApiErrorResponse(
            title: 'Delete Location Failed!',
            description: $this->message,
            status: Http::from($this->code),
        );
    }
}
