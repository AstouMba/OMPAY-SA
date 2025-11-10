<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Support\Facades\Log;

class OtpService
{
    /**
     * Generate and send OTP to client
     */
    public function generateOtp(Client $client): string
    {
        $otp = rand(100000, 999999); // 6-digit OTP
        $expiresAt = now()->addMinutes(5); // OTP expires in 5 minutes

        $client->update([
            'otp_code' => $otp,
            'otp_expires_at' => $expiresAt,
        ]);

        // Here you would integrate with SMS service to send the OTP
        // For now, we'll just log it
        Log::info("OTP for client {$client->telephone}: {$otp}");

        return $otp;
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Client $client, string $otp): bool
    {
        if ($client->otp_code === $otp && $client->otp_expires_at > now()) {
            // Clear OTP after successful verification
            $client->update([
                'otp_code' => null,
                'otp_expires_at' => null,
            ]);
            return true;
        }

        return false;
    }

    /**
     * Check if client has valid OTP
     */
    public function hasValidOtp(Client $client): bool
    {
        return $client->otp_code && $client->otp_expires_at > now();
    }
}