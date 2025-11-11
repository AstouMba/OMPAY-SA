<?php

namespace App\Http\Controllers;

use App\Enums\MessageEnumFr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Services\OtpService;
use App\Services\TransactionService;
use App\Traits\ApiResponses;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\VerifyOtpRequest;

class ClientAuthController extends Controller
{
    use ApiResponses;

    protected $otpService;
    protected $transactionService;

    public function __construct(
        OtpService $otpService,
        TransactionService $transactionService
    ) {
        $this->otpService = $otpService;
        $this->transactionService = $transactionService;
    }

    /**
     * Send OTP to client phone
     */
    public function sendOtp(SendOtpRequest $request)
    {

        $client = Client::parTelephone($request->telephone)->first();

        if (!$client) {
            return $this->errorResponse(MessageEnumFr::CLIENT_NON_TROUVE, 404);
        }

        // Check if client has an account
        if (!$client->comptes()->exists()) {
            return $this->errorResponse(MessageEnumFr::COMPTE_NON_TROUVE, 403);
        }

        $otp = $this->otpService->generateOtp($client);

        return $this->successResponse([
            'message' => MessageEnumFr::OTP_ENVOYE,
            'otp' => $otp, // Remove this in production, only for testing
        ]);
    }

    /**
     * Verify OTP and login client
     */
    public function verifyOtp(VerifyOtpRequest $request)
    {

        $client = Client::parTelephone($request->telephone)->first();

        if (!$client) {
            return $this->errorResponse(MessageEnumFr::CLIENT_NON_TROUVE, 404);
        }

        if (!$this->otpService->verifyOtp($client, $request->otp)) {
            return $this->errorResponse(MessageEnumFr::OTP_INVALIDE_OU_EXPIRE, 401);
        }

        // Create access token for client
        $token = $client->createToken('ClientToken');

        // Fetch additional data for dashboard
        $solde = $this->compteService->getSoldeForClient();
        $qrCode = $this->qrCodeService->generateClientQrCode($client);
        $transactions = $this->transactionService->getTransactionsForAuthenticatedClient($client->id, [], 10)->items();

        // Enrichir les transactions avec les informations des destinataires
        $transactions = $this->enrichirTransactionsAvecDestinataires($transactions);

        return $this->successResponse([
            'client' => $client->load('comptes'),
            'access_token' => $token->accessToken,
            'refresh_token' => $token->token->id, // Laravel Passport refresh token
            'token_type' => 'Bearer',
            'solde' => $solde,
            'qr_code' => $qrCode,
            'transactions' => $transactions,
        ], MessageEnumFr::LOGIN_REUSSI);
    }

    /**
     * Logout client
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return $this->successResponse(null, MessageEnumFr::LOGOUT_REUSSI);
    }


}
