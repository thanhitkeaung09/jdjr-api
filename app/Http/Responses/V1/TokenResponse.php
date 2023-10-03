<?php

declare(strict_types=1);

namespace App\Http\Responses\V1;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;
use Laravel\Sanctum\NewAccessToken;
use Symfony\Component\HttpFoundation\Response;

final readonly class TokenResponse implements Responsable
{
    public function __construct(
        private NewAccessToken $data,
        private Http $status = Http::OK,
    ) {
    }

    public function toResponse($request): Response
    {
        return new JsonResponse(
            data: [
                'token' => $this->data->plainTextToken,
            ],
            status: $this->status->value,
        );
    }
}
