<?php

declare(strict_types=1);

return [

    'service' => [
        'online' => 'Service Online',
    ],

    'register' => [
        'success' => 'Register successful.',
    ],

    'like' => 'Like successful.',
    'unlike' => 'Unlike successful.',

    'saved' => [
        'success' => 'Save successful.',
        'fail' => 'Remove successful.',
    ],

    'update' => [
        'success' => 'Update successful.',
        'fail' => 'Update failed!',
    ],

    'read' => [
        'success' => 'Read successful.',
        'fail' => 'Read failed!',
    ],

    'delete' => [
        'success' => 'Delete successful.',
        'fail' => 'Delete failed!',
    ],

    'password' => [
        'forgot' => 'Our system will send an OTP code to your email address.'
    ],

    'logout' => [
        'success' => 'Logout successful.',
    ],

    'login' => [

        'success' => 'Login successful.',

        'fail' => 'Login Failed!',
    ],

    'otp-resend' => [
        'success' => 'OTP code resend successful.',
    ],

    'exceptions' => [

        'wrong_password' => 'User credentials did not match!',

        'verified' => 'Your email is not verified yet!',

        'invalid_otp' => 'OTP code did not match!',

        'otp_expired' => 'The OTP code has expired. Please request a new OTP code!',

        'permission_denied' => 'You do not have permission to access it.',

        'invalid_app_keys' => 'Your keys are mismatch.',

        'email_not_verified' => 'This user is already register but still need verify process.',

        'title' => [

            'outdated' => 'Outdated!',

            'not_found' => 'Not Found Exception!',

            'unauthenicated' => 'Unauthenticated!',

            'unauthorized' => 'Unauthorized!',

            'invalid_otp' => 'Invalid OTP!',

            'validation' => 'Validation Error!',

            'delete_location' => 'Delete Location Failed!',

            'email_not_verified' => 'Email is not verified!',
        ],
    ],

    'validation' => [

        'unique' => 'The :attribute must be unique.',

        'count_gt_5' => 'The folder count must be 5.',
    ],

    'delete_location' => [

        'users_exist' => 'It cannot be deleted because there are users that exist in this location!',

        'jobs_exist' => 'It cannot be deleted because there are jobs that exist in this location!',
    ],

    'delete_category' => [

        'subcategories_exist' => 'It cannot be deleted because there are subcategories that exist in this category!',
    ],

    'delete_subcategory' => [

        'jobs_exist' => 'It cannot be deleted because there are job that exist in this subcategory!',
    ],

    'email' => [

        'unique' => 'This email is already exists!',

        'not_exists' => 'You need to create an account first.',

        'not_register' => 'User does not register with this email.'
    ],

    'app-version' => [

        'not_found' => 'App Version does not exist.',
    ],

    'folder' => [

        'invalid' => 'This folder id is invalid!',

        'not_own' => 'This folder is not yours!',
    ],
];
