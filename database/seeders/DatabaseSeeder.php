<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Appel des seeders individuels
        $this->call([
            AdminSeeder::class,
            MarchandSeeder::class,
            ClientSeeder::class,
            CompteSeeder::class,
            TransactionSeeder::class,
            TransactionFeeSeeder::class,
        ]);
    }
}
