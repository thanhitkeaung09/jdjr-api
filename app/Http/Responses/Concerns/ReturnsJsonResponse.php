<?php

declare(strict_types=1);

namespace App\Http\Responses\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JustSteveKing\StatusCode\Http;
use Symfony\Component\HttpFoundation\Response;

trait ReturnsJsonResponse
{
    /**
     * @param array|JsonResource|ResourceCollection|AnonymousResourceCollection $data
     * @param Http $status
     * @param string|null $warp
     */
    public function __construct(
        private readonly array|JsonResource|ResourceCollection|AnonymousResourceCollection $data,
        private readonly Http $status = Http::OK,
        private readonly string|null $warp = null
    ) {
    }

    public function toResponse($request): Response
    {
        $data = $this->warp ? [$this->warp => $this->data] : $this->data;

        return new JsonResponse(
            data: $data,
            status: $this->status->value,
            headers: [
                'Content-Type' => 'application/json',
            ],
        );
    }
}
