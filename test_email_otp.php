<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Client;
use App\Services\OtpService;

echo "Testing Email OTP functionality...\n\n";

// Create or find a test client
$testClient = Client::where('email', 'astou.odc@gmail.com')
                    ->orWhere('telephone', '221781157773')
                    ->first();
if (!$testClient) {
    $testClient = Client::create([
        'nom' => 'Astou',
        'prenom' => 'ODC',
        'telephone' => '221781157773',
        'nci' => 'ODC' . time(), // Use unique NCI
        'email' => 'astou.odc@gmail.com',
    ]);
    echo "Created new test client: {$testClient->nom} {$testClient->prenom}\n";
} else {
    echo "Using existing client: {$testClient->nom} {$testClient->prenom}\n";
    // Update both phone and email for testing
    $testClient->telephone = '221781157773';
    $testClient->email = 'astou.odc@gmail.com';
    $testClient->save();
}

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