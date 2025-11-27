<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Transaction;
use App\Services\QrCodeService;
use App\Traits\ApiResponses;
use App\Http\Resources\TransactionResource;
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
            ->with('marchand') // Charger la relation marchand
            ->get();

        // Formatter les transactions avec TransactionResource pour avoir les signes + et -
        $formattedTransactions = TransactionResource::collection($transactions);

        // Réformatter pour correspondre au format attendu avec type et telephone
        $transactionsData = $formattedTransactions->map(function ($transaction) use ($client) {
            $transactionArray = $transaction->toArray(request());

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
                'montant' => $transactionArray['montant'], // Déjà formaté avec + et - par TransactionResource
                'date_transaction' => $transaction->created_at->toISOString(),
            ];
        })->values(); // S'assurer que c'est une collection indexée

        // Generate QR code
        $qrCodeData = $this->qrCodeService->generateClientQrCode($client);

        $data = [
            'client' => [
                'id' => $client->id,
                'nom' => $client->prenom, // Utiliser seulement le prénom pour l'affichage
                'nom_complet' => $client->nom . ' ' . $client->prenom,
                'telephone' => $client->telephone,
                'nci' => $client->nci,
            ],
            'compte' => [
                'numero_compte' => $compte->numero_compte,
                'solde' => (float) $compte->solde, // Utiliser l'accessor du modèle qui calcule le solde
                'statut' => $compte->statut,
            ],
            'transactions' => $transactionsData->values()->toArray(), // Convertir en array simple
            'qrcode' => [
                'base64' => $qrCodeData['qr_code_base64'],
                'data' => $qrCodeData['qr_data']
            ],
        ];

        return $this->successResponse($data, 'Profil client récupéré avec succès');
    }
}