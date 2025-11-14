<?php

namespace App\Http\Controllers;

use App\Enums\MessageEnumFr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\Compte;
use App\Services\OtpService;
use App\Services\TransactionService;
use App\Services\QrCodeService;
use App\Traits\ApiResponses;
use Laravel\Passport\Token;

class ClientAuthController extends Controller
{
    use ApiResponses;

    protected $otpService;
    protected $transactionService;
    protected $qrCodeService;

    public function __construct(
        OtpService $otpService,
        TransactionService $transactionService,
        QrCodeService $qrCodeService
    ) {
        $this->otpService = $otpService;
        $this->transactionService = $transactionService;
        $this->qrCodeService = $qrCodeService;
    }


    /**
     * Send OTP for account activation
     */
    public function sendOtpActivation(Request $request)
    {
        $request->validate([
            'telephone' => 'required|string',
        ]);

        $client = Client::where('telephone', $request->telephone)->first();
        if (!$client) {
            return $this->errorResponse('Client non trouvé', 404);
        }

        $compte = $client->comptes()->first();
        if (!$compte || $compte->statut === 'actif') {
            return $this->errorResponse('Compte déjà activé', 400);
        }

        $otpCode = $this->otpService->generateAndSendOtp($request->telephone, 'activation');

        $data = config('twilio.services.sms.enabled') ? null : ['otp' => $otpCode];

        return $this->successResponse($data, 'Code OTP envoyé avec succès');
    }

    /**
     * Send OTP for login
     */
    public function login(Request $request)
    {
        $request->validate([
            'telephone' => 'required|string',
        ]);

        $client = Client::where('telephone', $request->telephone)->first();
        if (!$client) {
            return $this->errorResponse('Client non trouvé', 404);
        }

        $compte = $client->comptes()->first();
        if (!$compte || $compte->statut !== 'actif') {
            return $this->errorResponse('Votre compte n\'est pas encore activé', 400);
        }

        $otpCode = $this->otpService->generateAndSendOtp($request->telephone, 'login');

        $data = config('twilio.services.sms.enabled') ? null : ['otp' => $otpCode];

        return $this->successResponse($data, 'Code OTP envoyé avec succès');
    }

    /**
     * Verify OTP for activation or login
     */
    public function verifyOtpNew(Request $request)
    {
        $request->validate([
            'telephone' => 'required|string',
            'otp' => 'required|string|size:6',
        ]);

        $verification = $this->otpService->verifyOtpCode($request->telephone, $request->otp);
        if (!$verification) {
            return $this->errorResponse('Code OTP invalide ou expiré', 401);
        }

        $client = Client::where('telephone', $request->telephone)->first();

        if ($verification->type === 'activation') {
            // Activate account
            $compte = $client->comptes()->first();
            $compte->update(['statut' => 'actif']);
        }

        // Generate OAuth2 tokens
        $token = $client->createToken('ClientToken');
        $refreshToken = $token->token;

        return $this->successResponse([
            'access_token' => $token->accessToken,
            'refresh_token' => $refreshToken->id,
            'token_type' => 'Bearer',
        ], 'Connexion réussie');
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
