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
        $client = Client::where('telephone', '+221781157773')->first();

        if ($client) {
            Compte::firstOrCreate(
                ['numero_compte' => '+221781157773'],
                [
                    'id' => Str::uuid(),
                    'client_id' => $client->id,
                    'type_compte' => 'ompay',
                    'devise' => 'FCFA',
                    'solde' => 100000,
                    'est_supprime' => false,
                ]
            );
        }
    }
}
