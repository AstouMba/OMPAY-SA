<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Transaction;
use App\Services\QrCodeService;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    use ApiResponses;

    protected $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * Get authenticated client profile with account, transactions and QR code
     */
    public function compte(Request $request)
    {
        $client = Auth::user();

        if (!$client) {
            return $this->errorResponse('Client non authentifié', 401);
        }

        $compte = $client->comptes()->first();

        if (!$compte) {
            return $this->errorResponse('Aucun compte trouvé', 404);
        }

        // Get all transactions for this client's account
        $transactions = Transaction::where('compte_id', $compte->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($transaction) use ($client) {
                // Determine if transaction is incoming or outgoing based on type
                $isIncoming = in_array($transaction->type, ['depot', 'transfert_credit']);
                $isOutgoing = in_array($transaction->type, ['retrait', 'transfert_debit', 'paiement_marchand']);

                // Map transaction types to display types
                $displayType = match($transaction->type) {
                    'depot' => 'reception',
                    'retrait' => 'retrait',
                    'transfert_credit' => 'reception',
                    'transfert_debit' => 'transfert',
                    'paiement_marchand' => 'paiement',
                    default => $transaction->type
                };

                // Determine telephone to show (other party)
                $telephone = match($transaction->type) {
                    'transfert_credit', 'transfert_debit' => $transaction->telephone_marchand ?? $client->telephone,
                    'paiement_marchand' => $transaction->telephone_marchand ?? $client->telephone,
                    'retrait' => $client->telephone, // retrait shows client's own number
                    'depot' => $client->telephone, // depot shows client's own number
                    default => $client->telephone
                };

                return [
                    'type' => $displayType,
                    'telephone' => $telephone,
                    'montant' => $isIncoming ? $transaction->montant : -$transaction->montant,
                    'date_transaction' => $transaction->created_at->toISOString(),
                ];
            });

        // Generate QR code
        $qrCodeData = $this->qrCodeService->generateClientQrCode($client);
        $qrCode = $qrCodeData['qr_code_base64'];

        $data = [
            'client' => [
                'id' => $client->id,
                'nom' => $client->nom,
                'prenom' => $client->prenom,
                'telephone' => $client->telephone,
                'nci' => $client->nci,
                'statut' => $client->statut,
            ],
            'compte' => [
                'numero_compte' => $compte->numero_compte,
                'solde' => $compte->solde,
                'statut' => $compte->statut,
            ],
            'transactions' => $transactions,
            'qrcode' => $qrCode,
        ];

        return $this->successResponse($data, 'Profil client récupéré avec succès');
    }
}