<?php

namespace Database\Seeders;

use App\Models\Marchand;
use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Compte;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $compte = Compte::where('numero_compte', '+221781157773')->first();
        Transaction::create([
            'id' => Str::uuid(),
            'compte_id' => $compte->id,
            'type' => 'depot',
            'montant' => 100000,
            'statut' => 'validee',
        ]);

        Transaction::create([
            'id' => Str::uuid(),
            'compte_id' => $compte->id,
            'type' => 'retrait',
            'montant' => 20000,
            'statut' => 'validee',
        ]);

        Transaction::create([
            'id' => Str::uuid(),
            'compte_id' => $compte->id,
            'type' => 'transfert_debit',
            'montant' => 30000,
            'statut' => 'validee',
        ]);

        Transaction::create([
            'id' => Str::uuid(),
            'compte_id' => $compte->id,
            'type' => 'transfert_credit',
            'montant' => 50000,
            'statut' => 'validee',
        ]);

        $marchand = Marchand::first();
        if ($marchand) {
            Transaction::create([
                'id' => Str::uuid(),
                'compte_id' => $compte->id,
                'marchand_id' => $marchand->id,
                'type' => 'paiement_marchand',
                'montant' => 15000,
                'statut' => 'validee',
            ]);
        }
    }
}
