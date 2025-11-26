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

/**
 * Contrôleur d'authentification pour les clients OmPay
 * Gère l'envoi OTP, la vérification OTP et la génération de tokens d'accès
 */
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

        // Always return meaningful data about OTP status
        $data = [
            'otp_sent' => env('OTP_SEND_EMAIL', false),
            'message' => env('OTP_SEND_EMAIL', false) 
                ? 'Code OTP envoyé par email' 
                : 'Code OTP généré (email désactivé)',
            'otp' => env('OTP_SEND_EMAIL', false) ? null : $otpCode
        ];

        return $this->successResponse($data, 'Code OTP envoyé avec succès');
    }

    /**
     * Send OTP for login
     * 
     * @OA\Post(
     *     path="/api/v1/compte/login",
     *     summary="Demande d'envoi de code OTP pour connexion client",
     *     tags={"Authentification Client"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"telephone"},
     *             @OA\Property(property="telephone", type="string", example="+221781157773", description="Numéro de téléphone du client")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Code OTP envoyé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Code OTP envoyé avec succès"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="otp_sent", type="boolean", example=true),
     *                 @OA\Property(property="message", type="string", example="Code OTP envoyé par email"),
     *                 @OA\Property(property="otp", type="string", nullable=true, example=null),
     *                 @OA\Property(property="temp_token", type="string", example="R7Nbr5mna7bN9CSgK91eTKqOlNj8iju1", description="Token temporaire pour la vérification OTP")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Client non trouvé"),
     *     @OA\Response(response=400, description="Compte non activé")
     * )
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

        // Generate temporary token to remember telephone for verification
        $tempToken = \Illuminate\Support\Str::random(32);
        
        // Store telephone in cache for 10 minutes with the temp token
        \Illuminate\Support\Facades\Cache::put("otp_temp_token:{$tempToken}", $request->telephone, 600);

        // Always return meaningful data about OTP status
        $data = [
            'otp_sent' => env('OTP_SEND_EMAIL', false),
            'message' => env('OTP_SEND_EMAIL', false) 
                ? 'Code OTP envoyé par email' 
                : 'Code OTP généré (email désactivé)',
            'otp' => env('OTP_SEND_EMAIL', false) ? null : $otpCode,
            'temp_token' => $tempToken // Token pour la vérification
        ];

        return $this->successResponse($data, 'Code OTP envoyé avec succès');
    }

    /**
     * Verify OTP for activation or login
     * Documentation is in app/Docs/AuthApi.php
     */
    public function verifyOtpNew(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        // Find the OTP verification record by OTP code
        $verification = \App\Models\OtpVerification::where('code', $request->otp)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();
        if (!$verification) {
            return $this->errorResponse('Code OTP invalide ou expiré', 401);
        }

        // Get the client using the telephone from the OTP record
        $client = Client::where('telephone', $verification->telephone)->first();
        if (!$client) {
            return $this->errorResponse('Client non trouvé', 404);
        }

        $compte = $client->comptes()->first();
        if (!$compte) {
            return $this->errorResponse('Compte client non trouvé', 404);
        }

        // Mark the OTP as used
        $verification->update(['is_used' => true]);

        // If this is an activation OTP, activate the account
        if ($verification->type === 'activation' && $compte->statut !== 'actif') {
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
     * 
     * @OA\Post(
     *     path="/api/v1/compte/logout",
     *     summary="Déconnexion du client",
     *     tags={"Authentification Client"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Déconnexion réussie",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Déconnexion réussie")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié")
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return $this->successResponse(null, MessageEnumFr::LOGOUT_REUSSI);
    }


}
