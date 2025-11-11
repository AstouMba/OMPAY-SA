<?php

namespace App\Services;

use App\Models\TransactionFee;

class FeeService
{
    /**
     * Valeurs par défaut des frais si aucune configuration n'existe
     */
    private const DEFAULT_FEES = [
        'transfert' => 0.008, // 0.8%
        'paiement_marchand' => 0.005, // 0.5%
        'depot' => 0.0, // 0%
        'retrait' => 0.002, // 0.2%
    ];

    /**
     * Calculer les frais pour un montant et un type de transaction
     */
    public function calculateFees($montant, $typeTransaction = 'transfert')
    {
        $feeConfig = TransactionFee::actif()
            ->pourType($typeTransaction)
            ->first();

        if ($feeConfig) {
            // Utiliser la configuration de la base de données
            return $feeConfig->calculerFrais($montant);
        }

        // Fallback aux valeurs par défaut
        return $this->calculateDefaultFees($montant, $typeTransaction);
    }

    /**
     * Calculer les frais avec les valeurs par défaut
     */
    private function calculateDefaultFees($montant, $typeTransaction)
    {
        $percentage = self::DEFAULT_FEES[$typeTransaction] ?? self::DEFAULT_FEES['transfert'];
        return round($montant * $percentage, 2);
    }

    /**
     * Obtenir la configuration des frais pour un type de transaction
     */
    public function getFeeConfig($typeTransaction)
    {
        return TransactionFee::actif()
            ->pourType($typeTransaction)
            ->first();
    }

    /**
     * Obtenir toutes les configurations de frais
     */
    public function getAllFeeConfigs()
    {
        return TransactionFee::orderBy('type_transaction')
            ->orderBy('actif', 'desc')
            ->get();
    }

    /**
     * Créer ou mettre à jour une configuration de frais
     */
    public function setFeeConfig($typeTransaction, $pourcentageFrais, $fraisFixe = 0, $actif = true)
    {
        return TransactionFee::updateOrCreate(
            ['type_transaction' => $typeTransaction],
            [
                'pourcentage_frais' => $pourcentageFrais,
                'frais_fixe' => $fraisFixe,
                'actif' => $actif
            ]
        );
    }

    /**
     * Désactiver une configuration de frais
     */
    public function deactivateFeeConfig($typeTransaction)
    {
        return TransactionFee::pourType($typeTransaction)
            ->update(['actif' => false]);
    }
}