<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Models\Compte;
use Illuminate\Support\Facades\Log;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     * Met à jour automatiquement le solde du compte lors de la création d'une transaction
     */
    public function created(Transaction $transaction): void
    {
        $this->updateAccountBalance($transaction);
        Log::info("Transaction créée et solde mis à jour", [
            'transaction_id' => $transaction->id,
            'compte_id' => $transaction->compte_id,
            'type' => $transaction->type,
            'montant' => $transaction->montant,
            'statut' => $transaction->statut
        ]);
    }

    /**
     * Met à jour le solde du compte en fonction du type et statut de la transaction
     */
    private function updateAccountBalance(Transaction $transaction): void
    {
        $compte = $transaction->compte;

        if (!$compte) {
            Log::error("Compte non trouvé pour la transaction", ['transaction_id' => $transaction->id]);
            return;
        }

        // Ne mettre à jour le solde que si la transaction est validée
        if ($transaction->statut !== 'validee') {
            return;
        }

        $montant = $transaction->montant;

        // Logique de mise à jour du solde selon le type de transaction
        switch ($transaction->type) {
            case 'depot':
                // Dépôt : augmenter le solde
                $compte->increment('solde', $montant);
                break;

            case 'retrait':
                // Retrait : diminuer le solde
                $compte->decrement('solde', $montant);
                break;

            case 'transfert_credit':
                // Transfert entrant : augmenter le solde
                $compte->increment('solde', $montant);
                break;

            case 'transfert_debit':
                // Transfert sortant : diminuer le solde
                $compte->decrement('solde', $montant);
                break;

            case 'paiement_marchand':
                // Paiement marchand : diminuer le solde
                $compte->decrement('solde', $montant);
                break;

            default:
                Log::warning("Type de transaction non reconnu", [
                    'transaction_id' => $transaction->id,
                    'type' => $transaction->type
                ]);
                break;
        }

        Log::info("Solde du compte mis à jour", [
            'compte_id' => $compte->id,
            'nouveau_solde' => $compte->solde,
            'transaction_type' => $transaction->type,
            'montant' => $montant
        ]);
    }

    /**
     * Handle the Transaction "updated" event.
     * Met à jour le solde si le statut change (ex: de 'en_attente' à 'validee')
     */
    public function updated(Transaction $transaction): void
    {
        if ($transaction->isDirty('statut')) {
            $this->updateAccountBalance($transaction);
            Log::info("Statut de transaction modifié et solde mis à jour", [
                'transaction_id' => $transaction->id,
                'ancien_statut' => $transaction->getOriginal('statut'),
                'nouveau_statut' => $transaction->statut
            ]);
        }
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "restored" event.
     */
    public function restored(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "force deleted" event.
     */
    public function forceDeleted(Transaction $transaction): void
    {
        //
    }
}
