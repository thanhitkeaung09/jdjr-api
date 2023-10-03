<x-mail::message>
    Dear {{ $user ? $user->name : 'Customer' }},

    We have received a request to reset your password for your account. To proceed with the password reset, please use the following One-Time Password (OTP) code:

    OTP Code: [{{ $otp->otp }}]

    This code is valid for 1 minute. Please enter it in the otp confirm form to reset your password.

    If you did not request a password reset, please ignore this email and ensure the security of
    your account.

    Thank you for your attention to this matter.

    Best regards,<br>
    {{ config('app.name') }}
</x-mail::message>
