<?php

namespace App\Services;

use App\Models\Client;
use App\Models\OtpVerification;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client as TwilioClient;

class OtpService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new TwilioClient(
            config('twilio.sid'),
            config('twilio.token')
        );
    }

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

        // Send OTP via Twilio SMS if enabled
        if (config('twilio.services.sms.enabled')) {
            try {
                $this->sendOtpSms($client->telephone, $otp);
                Log::info("OTP sent successfully to client {$client->telephone}");
            } catch (\Exception $e) {
                Log::error("Failed to send OTP to {$client->telephone}: " . $e->getMessage());
                // Continue execution - OTP is still stored in database
            }
        } else {
            Log::info("OTP SMS disabled - Code for {$client->telephone}: {$otp}");
        }

        return $otp;
    }

    /**
     * Send OTP via Twilio SMS
     */
    protected function sendOtpSms(string $phoneNumber, string $otp): void
    {
        // Format phone number for Senegal (add +221 if not present)
        $formattedPhone = $this->formatPhoneNumber($phoneNumber);

        $message = $this->twilio->messages->create(
            $formattedPhone,
            [
                'from' => config('twilio.from'),
                'body' => "Votre code de vérification OMPay est : {$otp}. Ce code expire dans 5 minutes."
            ]
        );

        Log::info("Twilio SMS sent", [
            'message_sid' => $message->sid,
            'to' => $formattedPhone,
            'status' => $message->status
        ]);
    }

    /**
     * Format phone number for Senegal
     */
    protected function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove any spaces, dashes, or other non-numeric characters
        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

        // If it starts with 221, add +
        if (str_starts_with($phoneNumber, '221')) {
            return '+' . $phoneNumber;
        }

        // If it starts with 0, replace with +221
        if (str_starts_with($phoneNumber, '0')) {
            return '+221' . substr($phoneNumber, 1);
        }

        // If it's just the local number, add +221
        if (strlen($phoneNumber) === 9) {
            return '+221' . $phoneNumber;
        }

        // If it doesn't start with +, add it
        if (!str_starts_with($phoneNumber, '+')) {
            return '+' . $phoneNumber;
        }

        return $phoneNumber;
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

    /**
     * Generate and send OTP for activation or login
     */
    public function generateAndSendOtp(string $telephone, string $type): string
    {
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(5);

        OtpVerification::create([
            'telephone' => $telephone,
            'code' => $otp,
            'type' => $type,
            'expires_at' => $expiresAt,
            'is_used' => false,
        ]);

        // Send OTP via SMS if enabled
        if (config('twilio.services.sms.enabled')) {
            try {
                $this->sendOtpSms($telephone, $otp);
                Log::info("OTP sent successfully to {$telephone} for {$type}");
            } catch (\Exception $e) {
                Log::error("Failed to send OTP to {$telephone}: " . $e->getMessage());
            }
        } else {
            Log::info("OTP SMS disabled - Code for {$telephone} ({$type}): {$otp}");
        }

        return $otp;
    }

    /**
     * Verify OTP and return the verification record
     */
    public function verifyOtpCode(string $telephone, string $otp): ?OtpVerification
    {
        $verification = OtpVerification::valid($telephone)->latest()->first();

        if ($verification && $verification->code === $otp) {
            $verification->update(['is_used' => true]);
            return $verification;
        }

        return null;
    }
}