<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;
use Symfony\Component\HttpFoundation\Response;

final class ApiErrorResponse implements Responsable
{
    public function __construct(
        private readonly string $title,
        private readonly string $description,
        private readonly Http $status = Http::INTERNAL_SERVER_ERROR,
    ) {
    }

    public function toResponse($request): Response
    {
        return new JsonResponse(
            data: [
                'title' => $this->title,
                'description' => $this->description,
                'status' => $this->status->value,
            ],
            status: $this->status->value,
        );
    }
}
