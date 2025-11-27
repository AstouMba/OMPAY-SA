<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use Illuminate\Support\Str;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        // Client existant
        $client1 = Client::firstOrCreate(
            ['telephone' => '+221781157773'],
            [
                'id' => Str::uuid(),
                'nom' => 'Astou',
                'prenom' => 'Mbow',
                'nci' => '2234567890123',
                'email' => 'astou.odc@gmail.com',
            ]
        );

        // Client 2
        $client2 = Client::firstOrCreate(
            ['telephone' => '+221701234567'],
            [
                'id' => Str::uuid(),
                'nom' => 'Diallo',
                'prenom' => 'Amadou',
                'nci' => '1234567890123',
                'email' => 'amadou.diallo@gmail.com',
            ]
        );

        // Client 3
        $client3 = Client::firstOrCreate(
            ['telephone' => '+221702345678'],
            [
                'id' => Str::uuid(),
                'nom' => 'Ndiaye',
                'prenom' => 'Fatou',
                'nci' => '2234567890124',
                'email' => 'fatou.ndiaye@gmail.com',
            ]
        );

        // Client 4
        $client4 = Client::firstOrCreate(
            ['telephone' => '+221703456789'],
            [
                'id' => Str::uuid(),
                'nom' => 'Sarr',
                'prenom' => 'Oumar',
                'nci' => '3234567890123',
                'email' => 'oumar.sarr@gmail.com',
            ]
        );

        // Client 5
        $client5 = Client::firstOrCreate(
            ['telephone' => '+221704567890'],
            [
                'id' => Str::uuid(),
                'nom' => 'Ba',
                'prenom' => 'Aisha',
                'nci' => '4234567890123',
                'email' => 'aisha.ba@gmail.com',
            ]
        );
    }
}
