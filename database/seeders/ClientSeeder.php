<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use Illuminate\Support\Str;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        Client::firstOrCreate(
            ['telephone' => '+221781157773'],
            [
                'id' => Str::uuid(),
                'nom' => 'Astou',
                'prenom' => 'Mbow',
                'nci' => '2234567890123',
            ]
        );
    }
}
