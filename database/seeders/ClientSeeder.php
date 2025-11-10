<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use Illuminate\Support\Str;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        Client::create([
            'id' => Str::uuid(),
            'nom' => 'Astou',
            'prenom' => 'Mbow',
            'telephone' => '+221771234567',
            'nci' => '2234567890123',
        ]);
    }
}
