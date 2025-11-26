<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrevoEmailService
{
    protected $apiKey;
    protected $fromEmail;
    protected $fromName;

    public function __construct()
    {
        $this->apiKey = config('services.brevo.api_key', env('BREVO_API_KEY'));
        $this->fromEmail = config('mail.from.address', env('MAIL_FROM_ADDRESS'));
        $this->fromName = config('mail.from.name', env('MAIL_FROM_NAME', 'OmPay'));
    }

    /**
     * Envoyer un email via l'API HTTP Brevo
     */
    public function sendEmail(string $toEmail, string $subject, string $htmlContent, string $textContent = ''): bool
    {
        try {
            $response = Http::withHeaders([
                'api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name' => $this->fromName,
                    'email' => $this->fromEmail,
                ],
                'to' => [
                    [
                        'email' => $toEmail,
                        'name' => 'Client OmPay',
                    ],
                ],
                'subject' => $subject,
                'htmlContent' => $htmlContent,
                'textContent' => $textContent ?: strip_tags($htmlContent),
            ]);

            if ($response->successful()) {
                $messageId = $response->json()['messageId'] ?? 'N/A';
                Log::info("Email envoyé avec succès via Brevo API", [
                    'to' => $toEmail,
                    'subject' => $subject,
                    'message_id' => $messageId,
                ]);
                return true;
            } else {
                Log::error("Erreur envoi email Brevo", [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'to' => $toEmail,
                    'subject' => $subject,
                ]);
                return false;
            }

        } catch (\Exception $e) {
            Log::error("Exception envoi email Brevo", [
                'error' => $e->getMessage(),
                'to' => $toEmail,
                'subject' => $subject,
            ]);
            return false;
        }
    }

    /**
     * Envoyer un OTP email
     */
    public function sendOtpEmail(string $toEmail, string $otpCode, string $clientName, string $type = 'login'): bool
    {
        $typeLabel = $type === 'activation' ? 'Activation de votre compte' : 'Connexion à votre compte';
        $actionText = $type === 'activation' ? 'Activer mon compte' : 'Se connecter';
        
        $htmlContent = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <h2 style='color: #2563eb;'>Faysany Banque - OmPay</h2>
            
            <p>Bonjour {$clientName},</p>
            
            <p>Votre code de vérification OmPay pour {$typeLabel} est :</p>
            
            <div style='background-color: #f8fafc; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center;'>
                <h3 style='color: #dc2626; margin: 0; font-size: 28px; font-weight: bold;'>{$otpCode}</h3>
            </div>
            
            <p><strong>Ce code expire dans 5 minutes.</strong></p>
            
            <p>Si vous n'avez pas demandé ce code, ignorez cet email.</p>
            
            <p style='margin-top: 30px;'>Cordialement,<br>L'équipe Faysany Banque - OmPay</p>
        </div>
        ";

        $textContent = "Bonjour {$clientName},

Votre code de vérification OmPay pour {$typeLabel} est : {$otpCode}

Ce code expire dans 5 minutes.

Si vous n'avez pas demandé ce code, ignorez cet email.

Cordialement,
L'équipe Faysany Banque - OmPay";

        $subject = "Code de vérification OmPay - {$typeLabel}";
        
        return $this->sendEmail($toEmail, $subject, $htmlContent, $textContent);
    }
}