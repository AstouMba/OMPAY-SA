<?php

namespace App\Listeners;

use App\Events\OtpGenerated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendOtpNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OtpGenerated $event): void
    {
        $client = $event->client;
        $otpCode = $event->otpCode;

        // Log the OTP for development/testing
        Log::info("OTP envoyé au client {$client->telephone}: {$otpCode}");

        // Here you would integrate with SMS service
        // Example with a hypothetical SMS service:
        // SmsService::send($client->telephone, "Votre code OTP OmPay: {$otpCode}");

        // For now, we'll just log it
        // In production, replace this with actual SMS sending
    }

    /**
     * Handle a job failure.
     */
    public function failed(OtpGenerated $event, $exception): void
    {
        Log::error("Échec d'envoi OTP pour le client {$event->client->telephone}: {$exception->getMessage()}");
    }
}
