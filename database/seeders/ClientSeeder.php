<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use Illuminate\Support\Str;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $client = Client::firstOrCreate(
            ['telephone' => '+221781157773'],
            [
                'id' => Str::uuid(),
                'nom' => 'Astou',
                'prenom' => 'Mbow',
                'nci' => '2234567890123',
                'email' => 'astou.odc@gmail.com',
            ]
        );

        // S'assurer que l'email est toujours à jour
        if ($client->email !== 'astou.odc@gmail.com') {
            $client->update(['email' => 'astou.odc@gmail.com']);
        }
    }
}
