<?php

namespace App\Services;

use App\Repository\TransactionRepository;
use App\Services\FeeService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TransactionService
{
    protected $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * Récupère toutes les transactions avec pagination
     */
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->transactionRepository->paginate($perPage);
    }

    /**
     * Récupère toutes les transactions sans pagination
     */
    public function getAll(): Collection
    {
        return $this->transactionRepository->all();
    }

    /**
     * Récupère les transactions du client connecté avec filtres optionnels et pagination
     */
    public function getTransactionsForAuthenticatedClient(string $clientId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->transactionRepository->getClientTransactionsWithFilters($clientId, $filters, $perPage);
    }

    /**
     * Traite un paiement marchand
     */
    public function processPayment($clientId, $data)
    {
        // Validate data
        if (!isset($data['montant']) || $data['montant'] <= 0) {
            throw new \Exception('Montant invalide');
        }

        if (!isset($data['code_marchand']) && !isset($data['numero_marchand'])) {
            throw new \Exception('Code marchand ou numéro marchand requis');
        }

        // Find merchant
        $marchand = null;
        if (isset($data['code_marchand'])) {
            $marchand = \App\Models\Marchand::where('code_marchand', $data['code_marchand'])->first();
        } elseif (isset($data['numero_marchand'])) {
            $marchand = \App\Models\Marchand::where('telephone', $data['numero_marchand'])->first();
        }

        if (!$marchand) {
            throw new \Exception('Marchand non valide');
        }

        // Get client's active account
        $compteService = app(\App\Services\CompteService::class);
        $compte = $compteService->getActiveAccountForClient($clientId);
        if (!$compte) {
            throw new \Exception('Aucun compte actif trouvé');
        }

        // Check balance
        $soldeAvant = $compte->solde();
        if ($soldeAvant < $data['montant']) {
            throw new \Exception('Solde insuffisant');
        }

        // Create transaction (no fees for merchant payments)
        $transaction = \App\Models\Transaction::create([
            'compte_id' => $compte->id,
            'marchand_id' => $marchand->id,
            'telephone_marchand' => $marchand->telephone,
            'type' => 'paiement_marchand',
            'montant' => $data['montant'],
            'statut' => 'validee', // Immediate debit
        ]);

        // Calculate solde_apres
        $soldeApres = $soldeAvant - $data['montant'];

        return [
            'transaction_id' => $transaction->id,
            'type' => $transaction->type,
            'identifiant_marchand' => $marchand->code_marchand ?? $marchand->telephone,
            'montant' => '-' . $transaction->montant,
            'solde_apres' => $soldeApres,
            'date_creation' => $transaction->created_at->toISOString(),
        ];
    }

    /**
     * Traite un transfert vers un autre client
     */
    public function processTransfert($clientId, $data)
    {
        // Validate data
        if (!isset($data['montant']) || $data['montant'] <= 0) {
            throw new \Exception('Montant invalide');
        }

        if (!isset($data['numero_ompay'])) {
            throw new \Exception('Numéro OMPay du destinataire requis');
        }

        // Get sender's active account
        $compteService = app(\App\Services\CompteService::class);
        $senderAccount = $compteService->getActiveAccountForClient($clientId);
        if (!$senderAccount) {
            throw new \Exception('Aucun compte actif trouvé');
        }

        // Find recipient account by numero_ompay
        $recipientAccount = \App\Models\Compte::where('numero_compte', $data['numero_ompay'])
            ->where('type_compte', 'ompay')
            ->first();
        if (!$recipientAccount) {
            throw new \Exception('Destinataire non valide');
        }

        // Check if sender is not transferring to themselves
        if ($senderAccount->id === $recipientAccount->id) {
            throw new \Exception('Impossible de se transférer à soi-même');
        }

        // Calculate fees using FeeService
        $feeService = app(FeeService::class);
        $fees = $feeService->calculateFees($data['montant'], 'transfert');
        $totalDebit = $data['montant'] + $fees;

        // Check sender balance
        $senderBalance = $senderAccount->solde();
        if ($senderBalance < $totalDebit) {
            throw new \Exception('Solde insuffisant');
        }

        // Create debit transaction for sender
        $debitTransaction = \App\Models\Transaction::create([
            'compte_id' => $senderAccount->id,
            'type' => 'transfert_debit',
            'montant' => $totalDebit,
            'statut' => 'validee',
        ]);

        // Create credit transaction for recipient
        $creditTransaction = \App\Models\Transaction::create([
            'compte_id' => $recipientAccount->id,
            'type' => 'transfert_credit',
            'montant' => $data['montant'],
            'statut' => 'validee',
        ]);

        // Calculate sender's balance after transaction
        $senderBalanceAfter = $senderBalance - $totalDebit;

        // Get recipient client info
        $recipientClient = $recipientAccount->client;

        return [
            'transaction_id' => $debitTransaction->id,
            'type' => 'transfert',
            'montant' => $data['montant'],
            'frais' => $fees,
            'emetteur' => [
                'numero_ompay' => $senderAccount->numero_compte,
                'solde_apres' => $senderBalanceAfter,
            ],
            'destinataire' => [
                'numero_ompay' => $recipientAccount->numero_compte,
                'nom_prenom' => $recipientClient->nom . ' ' . $recipientClient->prenom,
                'montant_recu' => $data['montant'],
            ],
            'date_creation' => $debitTransaction->created_at->toISOString(),
        ];
    }
}