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
        $client = Client::first();

        if ($client) {
            Compte::create([
                'id' => Str::uuid(),
                'client_id' => $client->id,
                'numero_compte' => '+221771234567',
                'type_compte' => 'ompay',
                'devise' => 'FCFA',
                'est_supprime' => false,
            ]);
        }
    }
}
