<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Responses\ApiErrorResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use JustSteveKing\StatusCode\Http;

final class FallbackController
{
    public function __invoke(Request $request): Responsable
    {
        return new ApiErrorResponse(
            title: 'Oops!',
            description: 'Oops I broke something',
            status: Http::I_AM_A_TEAPOT,
        );
    }
}
