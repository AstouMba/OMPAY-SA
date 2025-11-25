<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Client;
use App\Services\OtpService;

echo "Testing Email OTP functionality...\n\n";

// Create or find a test client
$testClient = Client::first();
if (!$testClient) {
    $testClient = Client::create([
        'nom' => 'Test',
        'prenom' => 'Client',
        'telephone' => '771234567',
        'nci' => '123456789',
    ]);
    echo "Created new test client: {$testClient->nom} {$testClient->prenom}\n";
} else {
    echo "Using existing client: {$testClient->nom} {$testClient->prenom}\n";
}

// Update the client with an email for testing
$testClient->email = 'astoumbow51@gmail.com';
$testClient->save();

echo "Client email: {$testClient->email}\n\n";

// Test OTP generation and sending
$otpService = app(OtpService::class);

try {
    echo "Generating OTP for login...\n";
    $otp = $otpService->generateAndSendOtp($testClient->telephone, 'login');
    echo "OTP generated successfully: $otp\n";
    echo "Check the email inbox for {$testClient->email}\n";
} catch (Exception $e) {
    echo "Error generating OTP: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\nTest completed!\n";