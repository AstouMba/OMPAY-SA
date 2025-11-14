<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Twilio Credentials
    |--------------------------------------------------------------------------
    |
    | Your Twilio credentials can be found in your Twilio dashboard at
    | https://www.twilio.com/console
    |
    */

    'sid' => env('TWILIO_SID'),
    'token' => env('TWILIO_TOKEN'),
    'from' => env('TWILIO_PHONE'),

    /*
    |--------------------------------------------------------------------------
    | Twilio Services
    |--------------------------------------------------------------------------
    |
    | Configure which Twilio services you want to use.
    |
    */

    'services' => [
        'sms' => [
            'enabled' => env('OTP_SEND_SMS', true),
        ],
    ],
];