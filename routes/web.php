<?php

use Illuminate\Support\Facades\Route;
use L5Swagger\Http\Controllers\SwaggerController;

Route::get('/', fn () => view('welcome'));

// Routes de debug pour diagnostiquer les problèmes d'email en production
Route::get('/test-email-debug', function() {
    try {
        // Test de configuration email
        $config = [
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'username' => config('mail.mailers.smtp.username'),
            'encryption' => config('mail.mailers.smtp.encryption'),
            'from_address' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
        ];

        // Test d'envoi d'email
        \Illuminate\Support\Facades\Mail::raw('Test email depuis production OmPay', function($message) {
            $message->to('astoumbow51@gmail.com')
                   ->subject('Test Email Production - ' . date('Y-m-d H:i:s'));
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Email envoyé avec succès',
            'config' => $config,
            'timestamp' => now()->toDateTimeString()
        ]);

    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Erreur lors de l\'envoi d\'email',
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'config' => [
                'mailer' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'username' => config('mail.mailers.smtp.username'),
                'encryption' => config('mail.mailers.smtp.encryption'),
            ]
        ], 500);
    }
});

// Route pour tester la génération d'OTP
Route::get('/test-otp-debug', function() {
    try {
        $client = \App\Models\Client::where('telephone', '+221781157773')->first();
        
        if (!$client) {
            return response()->json([
                'status' => 'error',
                'message' => 'Client non trouvé'
            ], 404);
        }

        // Test OTP service
        $otpService = app(\App\Services\OtpService::class);
        $otp = $otpService->generateAndSendOtp($client->telephone, 'login');

        return response()->json([
            'status' => 'success',
            'message' => 'OTP généré et envoyé',
            'client' => [
                'id' => $client->id,
                'nom' => $client->nom,
                'prenom' => $client->prenom,
                'telephone' => $client->telephone,
                'email' => $client->email ?? 'Pas d\'email configuré'
            ],
            'otp_generated' => $otp,
            'email_enabled' => env('OTP_SEND_EMAIL', false),
            'timestamp' => now()->toDateTimeString()
        ]);

    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Erreur lors de la génération OTP',
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});


