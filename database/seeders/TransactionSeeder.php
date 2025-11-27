<?php

namespace Database\Seeders;

use App\Models\Compte;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer tous les comptes OmPay actifs
        $comptes = Compte::where('type_compte', 'ompay')
            ->where('statut', 'actif')
            ->get();

        foreach ($comptes as $compte) {
            $client = $compte->client;
            
            // Création de transactions de démonstration
            $this->createDemoTransactions($compte, $client);
        }
    }

    private function createDemoTransactions($compte, $client)
    {
        $now = now();
        
        // Dépôt initial (depuis l'extérieur)
        Transaction::create([
            'id' => Str::uuid(),
            'compte_id' => $compte->id,
            'type' => 'depot',
            'montant' => 50000,
            'statut' => 'validee',
            'telephone_marchand' => $client->telephone,
            'created_at' => $now->copy()->subDays(10),
        ]);

        // Un retrait
        Transaction::create([
            'id' => Str::uuid(),
            'compte_id' => $compte->id,
            'type' => 'retrait',
            'montant' => 10000,
            'statut' => 'validee',
            'telephone_marchand' => $client->telephone,
            'created_at' => $now->copy()->subDays(8),
        ]);

        // Paiement marchand
        Transaction::create([
            'id' => Str::uuid(),
            'compte_id' => $compte->id,
            'type' => 'paiement_marchand',
            'montant' => 5000,
            'statut' => 'validee',
            'telephone_marchand' => '+221700000001', // Numéro marchand
            'created_at' => $now->copy()->subDays(5),
        ]);

        // Transferts (si le client a un solde suffisant)
        if ($compte->solde > 20000) {
            // Transfert sortant
            Transaction::create([
                'id' => Str::uuid(),
                'compte_id' => $compte->id,
                'type' => 'transfert_debit',
                'montant' => 15000,
                'statut' => 'validee',
                'telephone_marchand' => '+221701234567', // Vers Amadou Diallo
                'created_at' => $now->copy()->subDays(3),
            ]);

            // Créer le crédit correspondant pour le destinataire
            $destinataireCompte = Compte::where('numero_compte', '+221701234567')->first();
            if ($destinataireCompte) {
                Transaction::create([
                    'id' => Str::uuid(),
                    'compte_id' => $destinataireCompte->id,
                    'type' => 'transfert_credit',
                    'montant' => 15000,
                    'statut' => 'validee',
                    'telephone_marchand' => $client->telephone,
                    'created_at' => $now->copy()->subDays(3),
                ]);
            }
        }

        // Transfert entrant (reçu d'un autre client)
        if ($client->telephone !== '+221702345678') { // Excepté Fatou Ndiaye
            $expediteur = Compte::where('numero_compte', '+221702345678')->first();
            if ($expediteur) {
                Transaction::create([
                    'id' => Str::uuid(),
                    'compte_id' => $compte->id,
                    'type' => 'transfert_credit',
                    'montant' => 8000,
                    'statut' => 'validee',
                    'telephone_marchand' => '+221702345678',
                    'created_at' => $now->copy()->subDays(1),
                ]);
            }
        }
    }
}
