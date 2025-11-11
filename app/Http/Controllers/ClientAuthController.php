<?php

namespace App\Http\Controllers;

use App\Enums\MessageEnumFr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Services\OtpService;
use App\Services\CompteService;
use App\Services\TransactionService;
use App\Services\QrCodeService;
use App\Traits\ApiResponses;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\VerifyOtpRequest;

class ClientAuthController extends Controller
{
    use ApiResponses;

    protected $otpService;
    protected $compteService;
    protected $transactionService;
    protected $qrCodeService;

    public function __construct(
        OtpService $otpService,
        CompteService $compteService,
        TransactionService $transactionService,
        QrCodeService $qrCodeService
    ) {
        $this->otpService = $otpService;
        $this->compteService = $compteService;
        $this->transactionService = $transactionService;
        $this->qrCodeService = $qrCodeService;
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

    /**
     * Get authenticated client
     */
    public function user(Request $request)
    {
        return $this->successResponse($request->user()->load('comptes'));
    }

    /**
     * Enrichir les transactions avec les informations des destinataires
     */
    public function enrichirTransactionsAvecDestinataires($transactions)
    {
        // Convertir les objets Eloquent en arrays
        $transactionsArray = array_map(function ($transaction) {
            return $transaction->toArray();
        }, $transactions);

        return array_map(function ($transaction) {
            $destinataire = null;

            switch ($transaction['type']) {
                case 'paiement_marchand':
                    if (isset($transaction['marchand']) && $transaction['marchand']) {
                        $destinataire = [
                            'type' => 'marchand',
                            'nom' => $transaction['marchand']['nom'],
                            'telephone' => $transaction['marchand']['telephone'] ?? $transaction['telephone_marchand']
                        ];
                    }
                    break;

                case 'transfert_debit':
                    // Pour les transferts sortants, chercher le client destinataire
                    // Note: Dans une vraie implémentation, il faudrait un champ client_destinataire_id
                    // Pour l'instant, on utilise une logique simplifiée
                    $destinataire = [
                        'type' => 'client',
                        'nom' => 'Client destinataire', // À remplacer par la vraie logique
                        'telephone' => 'N/A'
                    ];
                    break;

                case 'transfert_credit':
                    // Pour les transferts entrants, c'est l'expéditeur
                    $destinataire = [
                        'type' => 'client',
                        'nom' => 'Client expéditeur', // À remplacer par la vraie logique
                        'telephone' => 'N/A'
                    ];
                    break;

                default:
                    $destinataire = null;
                    break;
            }

            // Ajouter les informations du destinataire à la transaction
            $transaction['destinataire'] = $destinataire;

            return $transaction;
        }, $transactionsArray);
    }
}
