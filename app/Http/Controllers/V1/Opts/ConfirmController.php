<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Opts;

use App\Actions\V1\Otps\ConfirmOtp;
use App\Exceptions\InvalidOtpException;
use App\Http\Requests\V1\Otps\ConfirmRequest;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\V1\TokenResponse;
use Illuminate\Contracts\Support\Responsable;
use JustSteveKing\StatusCode\Http;

final class ConfirmController
{
    public function __construct(
        private ConfirmOtp $confirmOtp,
    ) {
    }

    public function __invoke(ConfirmRequest $request): Responsable
    {
        try {
            return new TokenResponse(
                data: $this->confirmOtp->handle(
                    data: $request->payload()
                ),
            );
        } catch (InvalidOtpException $e) {
            return new ApiErrorResponse(
                title: 'Invalid OTP!',
                description: $e->getMessage(),
                status: Http::UNPROCESSABLE_ENTITY,
            );
        }
    }
}
