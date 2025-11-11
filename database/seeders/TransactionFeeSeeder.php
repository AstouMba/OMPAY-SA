<?php

namespace Database\Seeders;

use App\Models\TransactionFee;
use Illuminate\Database\Seeder;

class TransactionFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fees = [
            [
                'type_transaction' => 'transfert',
                'pourcentage_frais' => 0.008, // 0.8%
                'frais_fixe' => 0,
                'actif' => true,
            ],
            [
                'type_transaction' => 'paiement_marchand',
                'pourcentage_frais' => 0.000, // 0%
                'frais_fixe' => 0,
                'actif' => true,
            ],
            [
                'type_transaction' => 'retrait',
                'pourcentage_frais' => 0.002, // 0.2%
                'frais_fixe' => 0,
                'actif' => true,
            ],
            [
                'type_transaction' => 'depot',
                'pourcentage_frais' => 0.000, // 0%
                'frais_fixe' => 0,
                'actif' => true,
            ],
        ];

        foreach ($fees as $fee) {
            TransactionFee::updateOrCreate(
                ['type_transaction' => $fee['type_transaction']],
                $fee
            );
        }
    }
}