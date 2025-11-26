<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test API HTTP Brevo ===\n\n";

use Illuminate\Support\Facades\Http;

$apiKey = env('BREVO_API_KEY');
$fromEmail = env('MAIL_FROM_ADDRESS');
$fromName = env('MAIL_FROM_NAME');

echo "Configuration:\n";
echo "API Key: " . (substr($apiKey, 0, 20) . "...") . "\n";
echo "From: $fromEmail ($fromName)\n\n";

try {
    // Test de l'API Brevo
    $response = Http::withHeaders([
        'api-key' => $apiKey,
        'Content-Type' => 'application/json',
    ])->post('https://api.brevo.com/v3/smtp/email', [
        'sender' => [
            'name' => $fromName,
            'email' => $fromEmail,
        ],
        'to' => [
            [
                'email' => 'astou.odc@gmail.com',
                'name' => 'Test Client',
            ],
        ],
        'subject' => 'Test Email OmPay - API HTTP',
        'htmlContent' => '<h1>Test Email OmPay</h1><p>Ceci est un test via l\'API HTTP de Brevo.</p>',
        'textContent' => 'Test Email OmPay - API HTTP',
    ]);

    echo "Réponse API:\n";
    echo "Status: " . $response->status() . "\n";
    echo "Body: " . $response->body() . "\n";

    if ($response->successful()) {
        echo "✅ Email envoyé avec succès via API HTTP!\n";
        echo "Message ID: " . ($response->json()['messageId'] ?? 'N/A') . "\n";
    } else {
        echo "❌ Erreur API:\n";
        echo $response->body() . "\n";
    }

} catch (Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
}

echo "\n=== Fin du test ===\n";