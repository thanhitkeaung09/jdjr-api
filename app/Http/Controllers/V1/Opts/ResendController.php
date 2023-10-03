<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Opts;

use App\Actions\V1\Otps\ResendOtp;
use App\Http\Requests\V1\Otps\ResendRequest;
use App\Http\Responses\V1\MessageResponse;
use Illuminate\Contracts\Support\Responsable;

final class ResendController
{
    public function __construct(
        private ResendOtp $resendOtp,
    ) {
    }

    public function __invoke(ResendRequest $request): Responsable
    {
        $this->resendOtp->handle($request->validated('email'));

        return new MessageResponse(
            data: [
                'message' => strval(\trans('message.otp-resend.success')),
            ]
        );
    }
}
