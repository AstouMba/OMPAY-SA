<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Compte;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Liste des clients pour créer des comptes OmPay
        $clientsData = [
            ['telephone' => '+221781157773', 'solde' => 150000, 'numero_compte' => '+221781157773'],
            ['telephone' => '+221701234567', 'solde' => 200000, 'numero_compte' => '+221701234567'],
            ['telephone' => '+221702345678', 'solde' => 75000,  'numero_compte' => '+221702345678'],
            ['telephone' => '+221703456789', 'solde' => 500000, 'numero_compte' => '+221703456789'],
            ['telephone' => '+221704567890', 'solde' => 10000,  'numero_compte' => '+221704567890'],
        ];

        foreach ($clientsData as $clientData) {
            $client = Client::where('telephone', $clientData['telephone'])->first();

            if ($client) {
                Compte::firstOrCreate(
                    ['numero_compte' => $clientData['numero_compte']],
                    [
                        'id' => Str::uuid(),
                        'client_id' => $client->id,
                        'type_compte' => 'ompay',
                        'devise' => 'FCFA',
                        'solde' => $clientData['solde'],
                        'statut' => 'actif',
                        'est_supprime' => false,
                    ]
                );
            }
        }
    }
}
