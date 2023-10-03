<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\MessageBag;
use JustSteveKing\StatusCode\Http;
use Symfony\Component\HttpFoundation\Response;

final class ApiValidationErrorResponse implements Responsable
{
    public function __construct(
        private readonly string $title,
        private readonly MessageBag $errors,
        private readonly Http $status = Http::UNPROCESSABLE_ENTITY,
    ) {
    }

    public function toResponse($request): Response
    {
        return new JsonResponse(
            data: [
                'title' => $this->title,
                'errors' => $this->errors,
                'status' => $this->status->value,
            ],
            status: $this->status->value,
        );
    }
}
